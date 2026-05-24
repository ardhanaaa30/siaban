import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime
import os

START_DATE = "2025-01-01"
HOURS = 24 * 181  # ~6 months (Jan to June)
FREQ = "h"
THRESHOLD_AMAN = 3.5
THRESHOLD_BAHAYA = 5.0
DELTA_SIAGA_TRIGGER = 0.3
WINDOW_SIZE = 5

OUTPUT_CSV = "Dataset/dataset_flood.csv"
OUTPUT_EXCEL = "Dataset/dataset_flood.xlsx"
REPORT_PATH = "reports/DATA_REPORT.md"
PLOT_DIR = "plots/analysis"

os.makedirs("Dataset", exist_ok=True)
os.makedirs("reports", exist_ok=True)
os.makedirs(PLOT_DIR, exist_ok=True)

def generate_base_timeline():
    """Generates the base datetime sequence."""
    print("Generating base timeline...")
    dt_range = pd.date_range(start=START_DATE, periods=HOURS, freq=FREQ)
    df = pd.DataFrame({
        'Datetime': dt_range,
        'Date': dt_range.strftime('%Y-%m-%d'),
        'Time': dt_range.strftime('%H:%M:%S')
    })
    return df

def generate_flood_events(length):
    """
    Generates realistic water level with base flow and flood events.
    Uses a combination of sinusoidal base and skewed gaussian peaks.
    """
    print("Simulating hydrological patterns...")
    t = np.arange(length)
    
    base_level = 2.5 + 0.4 * np.sin(2 * np.pi * t / (24 * 30)) + 0.1 * np.sin(2 * np.pi * t / 24)
    
    water_level = base_level.copy()
    num_events = np.random.randint(30, 42)
    
    event_starts = np.sort(np.random.choice(np.arange(100, length - 100), num_events, replace=False))
    
    for start_idx in event_starts:
        duration = np.random.randint(30, 100)
        peak_height = np.random.uniform(2.0, 4.0) 
        
        event_t = np.arange(duration)
        peak_pos = duration * 0.2
        
        sigma_rise = peak_pos / 1.5
        sigma_fall = (duration - peak_pos) / 2.2
        
        peak_shape = np.where(event_t < peak_pos,
                              np.exp(-((event_t - peak_pos)**2) / (2 * sigma_rise**2)),
                              np.exp(-((event_t - peak_pos)**2) / (2 * sigma_fall**2)))
        
        actual_peak = peak_shape * peak_height
        
        end_idx = min(start_idx + duration, length)
        water_level[start_idx:end_idx] += actual_peak[:end_idx-start_idx]

    noise = np.random.normal(0, 0.05, length)
    water_level += noise
    
    water_level = np.clip(water_level, a_min=None, a_max=7.0)
    
    water_level = pd.Series(water_level).rolling(window=3, min_periods=1, center=True).mean().values
    
    return water_level

def calculate_features(df):
    """Calculates Delta_Tinggi and Rolling_Mean."""
    print("Calculating features...")
    df['Tinggi_Air'] = df['Tinggi_Air'].round(2)
    df['Delta_Tinggi'] = df['Tinggi_Air'].diff().fillna(0).round(3)
    df['Rolling_Mean'] = df['Tinggi_Air'].rolling(window=WINDOW_SIZE, min_periods=1).mean().round(2)
    return df

def assign_status(row):
    """Assigns status based on rules."""
    val = row['Tinggi_Air']
    delta = row['Delta_Tinggi']
    
    if val >= THRESHOLD_BAHAYA:
        return 'Bahaya'
    elif val >= THRESHOLD_AMAN:
        return 'Siaga'
    elif delta >= DELTA_SIAGA_TRIGGER:
        return 'Siaga'
    else:
        return 'Aman'

def validate_dataset(df):
    """Performs validation checks."""
    print("Validating dataset...")
    issues = []
    
    if df['Datetime'].duplicated().any():
        issues.append("Error: Duplicate timestamps found.")
        
    expected_range = pd.date_range(start=df['Datetime'].min(), end=df['Datetime'].max(), freq=FREQ)
    if len(df) != len(expected_range):
        issues.append(f"Error: Missing timestamps. Expected {len(expected_range)}, got {len(df)}.")
        
    status_map = {'Aman': 0, 'Siaga': 1, 'Bahaya': 2}
    status_code = df['Status'].map(status_map)
    status_diff = status_code.diff().abs()
    illegal_jumps = df[status_diff > 1]
    if not illegal_jumps.empty:
        issues.append(f"Warning: {len(illegal_jumps)} instances of rapid status jumps (>1 step).")
        
    dist = df['Status'].value_counts(normalize=True)
    print(f"Distribution: \n{dist}")
    
    return issues, status_code

def generate_visualizations(df, status_code):
    """Creates plots for analysis."""
    print("Generating visualizations...")
    sns.set_theme(style="whitegrid")
    
    plt.figure(figsize=(15, 6))
    plt.plot(df['Datetime'], df['Tinggi_Air'], label='Tinggi Air', color='blue', alpha=0.7)
    plt.axhline(y=THRESHOLD_AMAN, color='orange', linestyle='--', label='Threshold Siaga')
    plt.axhline(y=THRESHOLD_BAHAYA, color='red', linestyle='--', label='Threshold Bahaya')
    plt.title('Flood Detection Dataset 2025 - Water Level Timeline')
    plt.ylabel('Tinggi Air (m)')
    plt.legend()
    plt.savefig(f"{PLOT_DIR}/timeline_water_level.png")
    plt.close()

    plt.figure(figsize=(8, 6))
    palette_map = {'Aman': 'green', 'Siaga': 'orange', 'Bahaya': 'red'}
    sns.countplot(x='Status', data=df, hue='Status', palette=palette_map, order=['Aman', 'Siaga', 'Bahaya'], legend=False)
    plt.title('Status Class Distribution')
    plt.savefig(f"{PLOT_DIR}/status_distribution.png")
    plt.close()

    df_temp = df.copy()
    df_temp['Hour'] = df['Datetime'].dt.hour
    df_temp['Day'] = df['Datetime'].dt.date
    df_temp['Status_Code'] = status_code
    pivot_df = df_temp.pivot(index='Day', columns='Hour', values='Status_Code')
    plt.figure(figsize=(12, 10))
    sns.heatmap(pivot_df, cmap='RdYlGn_r', cbar_kws={'label': '0:Aman, 1:Siaga, 2:Bahaya'})
    plt.title('Temporal Status Heatmap (Day vs Hour)')
    plt.savefig(f"{PLOT_DIR}/status_heatmap.png")
    plt.close()

    bahaya_indices = df[df['Status'] == 'Bahaya'].index
    if not bahaya_indices.empty:
        idx = bahaya_indices[0]
        start_zoom = max(0, idx - 48)
        end_zoom = min(len(df), idx + 72)
        zoom_df = df.iloc[start_zoom:end_zoom]
        
        plt.figure(figsize=(12, 5))
        plt.plot(zoom_df['Datetime'], zoom_df['Tinggi_Air'], marker='o', markersize=3)
        plt.fill_between(zoom_df['Datetime'], zoom_df['Tinggi_Air'], alpha=0.2)
        plt.axhline(y=THRESHOLD_AMAN, color='orange', linestyle='--')
        plt.axhline(y=THRESHOLD_BAHAYA, color='red', linestyle='--')
        plt.title('Detailed View of a Flood Event')
        plt.savefig(f"{PLOT_DIR}/flood_event_zoom.png")
        plt.close()

def save_report(df, issues):
    """Generates the final report."""
    print("Saving report...")
    dist = df['Status'].value_counts()
    dist_pct = df['Status'].value_counts(normalize=True) * 100
    
    report_content = f"""# FLOOD DATASET REPORT 2025

## Dataset Summary
- **Period**: {df['Datetime'].min()} to {df['Datetime'].max()}
- **Frequency**: Hourly (1H)
- **Total Records**: {len(df)} rows

## Class Distribution
| Status | Count | Percentage |
|--------|-------|------------|
| Aman   | {dist.get('Aman', 0)} | {dist_pct.get('Aman', 0):.2f}% |
| Siaga  | {dist.get('Siaga', 0)} | {dist_pct.get('Siaga', 0):.2f}% |
| Bahaya | {dist.get('Bahaya', 0)} | {dist_pct.get('Bahaya', 0):.2f}% |

## Hydrological Stats
- **Min Tinggi Air**: {df['Tinggi_Air'].min():.2f} m
- **Max Tinggi Air**: {df['Tinggi_Air'].max():.2f} m
- **Mean Tinggi Air**: {df['Tinggi_Air'].mean():.2f} m
- **Max Delta (Rise)**: {df['Delta_Tinggi'].max():.2f} m/hour

## Validation Results
{chr(10).join(['- ' + i for i in issues]) if issues else '✅ All validation checks passed.'}

## Methodology
The dataset was generated using:
1. **Sinusoidal Base Flow**: Simulates seasonal and diurnal patterns.
2. **Skewed Gaussian Flood Events**: Random events with fast-rise and slow-decay characteristics.
3. **Temporal Smoothing**: Rolling window filters to ensure continuity.
4. **Feature Engineering**: Delta calculations and rolling averages for LSTM context.
5. **Rule-based Labeling**: Hybrid logic using static thresholds and rise-velocity triggers.

Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}
"""
    with open(REPORT_PATH, 'w', encoding='utf-8') as f:
        f.write(report_content)

def main():
    df = generate_base_timeline()
    
    df['Tinggi_Air'] = generate_flood_events(len(df))
    
    df = calculate_features(df)
    
    df['Status'] = df.apply(assign_status, axis=1)
    
    issues, status_code = validate_dataset(df)
    
    generate_visualizations(df, status_code)
    
    print(f"Saving to {OUTPUT_CSV}...")
    df.to_csv(OUTPUT_CSV, index=False)
    
    try:
        print(f"Saving to {OUTPUT_EXCEL}...")
        df_excel = df.copy()
        df_excel['Datetime'] = df_excel['Datetime'].dt.strftime('%Y-%m-%d %H:%M:%S')
        df_excel.to_excel(OUTPUT_EXCEL, index=False)
    except Exception as e:
        print(f"Warning: Could not save Excel file. {e}")
        
    save_report(df, issues)
    
    print("Done! Dataset generation complete.")

if __name__ == "__main__":
    main()