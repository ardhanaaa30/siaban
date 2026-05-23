from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Dict, Optional
import numpy as np
import pandas as pd
import os
import joblib
import warnings

# Suppress TF warnings for clean logs
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
warnings.filterwarnings('ignore')

try:
    from tensorflow.keras.models import load_model
    TF_AVAILABLE = True
except ImportError:
    TF_AVAILABLE = False

app = FastAPI(title="Flood Detection LSTM API")

# Setup paths
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, 'models', 'best_lstm_model.keras')
SCALER_PATH = os.path.join(BASE_DIR, 'models', 'minmax_scaler.pkl')
ENCODER_PATH = os.path.join(BASE_DIR, 'models', 'label_encoder.pkl')

# Load models globally
model = None
scaler = None
encoder = None

def load_resources():
    global model, scaler, encoder
    try:
        if TF_AVAILABLE and os.path.exists(MODEL_PATH):
            model = load_model(MODEL_PATH)
        if os.path.exists(SCALER_PATH):
            scaler = joblib.load(SCALER_PATH)
        if os.path.exists(ENCODER_PATH):
            encoder = joblib.load(ENCODER_PATH)
    except Exception as e:
        print(f"Warning: Could not load models. Exception: {str(e)}")

load_resources()

class PredictionRequest(BaseModel):
    sequence: List[float]

class PredictionResponse(BaseModel):
    prediction: str
    confidence: float
    probabilities: Dict[str, float]
    sequence: Optional[List[float]] = None

@app.get("/")
def read_root():
    return {"status": "active", "message": "Flood Prediction API is running"}

@app.post("/predict", response_model=PredictionResponse)
def predict(request: PredictionRequest):
    sequence = request.sequence
    
    # Validation
    if not sequence:
        raise HTTPException(status_code=400, detail="Sequence cannot be empty")
    
    # ---------------------------------------------------------
    # INFERENCE LOGIC (Reusable)
    # ---------------------------------------------------------
    def run_inference(seq):
        # Fallback Mock
        if model is None or scaler is None or encoder is None:
            latest_val = seq[-1]
            if latest_val > 2.5:
                pred = "Bahaya"
                probs = {"Aman": 0.05, "Siaga": 0.15, "Bahaya": 0.80}
            elif latest_val > 2.0:
                pred = "Siaga"
                probs = {"Aman": 0.10, "Siaga": 0.75, "Bahaya": 0.15}
            else:
                pred = "Aman"
                probs = {"Aman": 0.90, "Siaga": 0.08, "Bahaya": 0.02}
            return pred, max(probs.values()), probs

        # Actual Inference
        latest_val = seq[-1]
        rule_based_pred = "Aman"
        
        # User thresholds: 
        # Tinggi < 3.5m: Aman
        # 3.5m - 5m: Siaga
        # >= 5m: Bahaya
        if latest_val >= 5.0:
            rule_based_pred = "Bahaya"
        elif latest_val >= 3.5:
            rule_based_pred = "Siaga"
            
        num_features = 8
        target_timesteps = 24
        
        if len(seq) < target_timesteps:
            pad_len = target_timesteps - len(seq)
            sequence_padded = [seq[0]] * pad_len + seq
        else:
            sequence_padded = seq[-target_timesteps:]
            
        data = np.zeros((target_timesteps, num_features))
        data[:, 0] = sequence_padded # Water level in 1st column
        
        scaled_data = scaler.transform(data)
        lstm_input = scaled_data.reshape(1, target_timesteps, num_features)
        
        raw_pred = model.predict(lstm_input, verbose=0)
        probabilities = raw_pred[0]
        class_index = np.argmax(probabilities)
        lstm_pred = encoder.inverse_transform([class_index])[0]
        
        # 3. Hybrid Logic: Use Rule-based as floor, LSTM can escalate but not de-escalate safety
        status_priority = {"Aman": 0, "Siaga": 1, "Bahaya": 2}

        final_pred = rule_based_pred
        final_confidence = 1.0 # Default for rule-based

        # Format output probabilities
        classes = encoder.classes_
        prob_dict = {str(cls): float(prob) for cls, prob in zip(classes, probabilities)}

        if status_priority.get(lstm_pred, 0) > status_priority.get(rule_based_pred, 0):
            # AI detected a more dangerous trend, use AI prediction and its confidence
            final_pred = lstm_pred
            final_confidence = float(prob_dict.get(str(lstm_pred), 0.0))
        else:
            # Rule-based logic is dominant or matches AI, it's 100% certain based on height
            final_confidence = 1.0

        return str(final_pred), final_confidence, prob_dict

    try:
        pred, conf, probs = run_inference(sequence)
        return PredictionResponse(
            prediction=pred,
            confidence=conf,
            probabilities=probs,
            sequence=sequence
        )
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Inference error: {str(e)}")

@app.post("/predict-excel", response_model=PredictionResponse)
def predict_excel():
    # Path to public/data_realtime.xlsx
    excel_path = os.path.join(os.path.dirname(BASE_DIR), 'public', 'data_realtime.xlsx')
    
    if not os.path.exists(excel_path):
        raise HTTPException(status_code=404, detail=f"Excel file not found at {excel_path}")
        
    try:
        df = pd.read_excel(excel_path)
        col = 'Tinggi ' if 'Tinggi ' in df.columns else 'Tinggi'
        
        if col not in df.columns:
            raise HTTPException(status_code=400, detail="Water level column not found in Excel")
            
        # Extract last 24 values
        sequence = []
        for val in df[col].tail(24).values:
            if isinstance(val, str):
                val = val.replace(' m', '').replace(',', '.')
            try:
                sequence.append(float(val))
            except (ValueError, TypeError):
                continue
                
        if not sequence:
            raise HTTPException(status_code=400, detail="No valid data in Excel")
            
        return predict(PredictionRequest(sequence=sequence))
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Excel processing error: {str(e)}")

@app.get("/excel-data")
def get_excel_data():
    excel_path = os.path.join(os.path.dirname(BASE_DIR), 'public', 'data_realtime.xlsx')
    
    if not os.path.exists(excel_path):
        raise HTTPException(status_code=404, detail="Excel file not found")
        
    try:
        df = pd.read_excel(excel_path)
        col = 'Tinggi ' if 'Tinggi ' in df.columns else 'Tinggi'
        
        records = []
        for _, row in df.iterrows():
            val = row[col]
            if isinstance(val, str):
                val = val.replace(' m', '').replace(',', '.')
            
            try:
                tinggi = float(val)
            except:
                continue
                
            records.append({
                "Tanggal": str(row['Tanggal']),
                "Waktu": str(row['Waktu']),
                "Tinggi": tinggi
            })
            
        return {"records": records}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="127.0.0.1", port=8002)
