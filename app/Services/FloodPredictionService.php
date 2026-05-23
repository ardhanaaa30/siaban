<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PredictionLog;
use Exception;

class FloodPredictionService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.flood_api.url', 'http://127.0.0.1:8002');
    }

    /**
     * Get prediction from Python API
     * 
     * @param array $sequence Array of water levels (last 24 recommended)
     * @return array
     * @throws Exception
     */
    public function getPrediction(array $sequence): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->apiUrl}/predict", [
                'sequence' => $sequence
            ]);

            if ($response->failed()) {
                Log::error('Flood API Error: ' . $response->body());
                throw new Exception('Gagal menghubungi layanan prediksi AI.');
            }

            $data = $response->json();

            // Log prediction to database
            PredictionLog::create([
                'input_data' => json_encode($sequence),
                'prediction' => $data['prediction'],
                'probabilities' => json_encode($data['probabilities']),
            ]);

            return $data;
        } catch (Exception $e) {
            Log::error('FloodPredictionService Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
