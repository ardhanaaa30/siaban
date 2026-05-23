<?php

namespace App\Services;

use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ExcelSyncService
{
    /**
     * Synchronize sensor readings from Excel file via Python API or direct read
     * For simplicity and consistency, we'll add an endpoint to our Python API 
     * that returns the full dataset from Excel.
     */
    public function syncFromExcel(): void
    {
        try {
            // We'll call the Python API to get all data from Excel
            // This ensures parsing logic is centralized in Python
            $apiUrl = config('services.flood_api.url', 'http://127.0.0.1:8002');
            $response = Http::timeout(10)->get("{$apiUrl}/excel-data");

            if ($response->failed()) {
                Log::error('ExcelSyncService: Failed to fetch data from Python API');
                return;
            }

            $data = $response->json();
            
            if (empty($data['records'])) {
                return;
            }

            foreach ($data['records'] as $record) {
                // Parse date and time
                // Excel format: 21/11/2025  22:17:44
                try {
                    $datetime = Carbon::createFromFormat('d/m/Y H:i:s', "{$record['Tanggal']} {$record['Waktu']}");
                } catch (\Exception $e) {
                    continue;
                }

                $tinggiAir = (float) $record['Tinggi'];
                $status = $this->calculateStatus($tinggiAir);

                // Update or create reading
                SensorReading::updateOrCreate(
                    ['datetime' => $datetime],
                    [
                        'tinggi_air' => $tinggiAir,
                        'status_prediksi' => $status,
                        'confidence' => 1.0, // Base data is 100% confident
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('ExcelSyncService Error: ' . $e->getMessage());
        }
    }

    /**
     * Tinggi < 3.5m: Aman
     * 3.5m - 5m: Siaga
     * >= 5m: Bahaya
     */
    private function calculateStatus(float $tinggi): string
    {
        if ($tinggi >= 5.0) {
            return 'Bahaya';
        } elseif ($tinggi >= 3.5) {
            return 'Siaga';
        }
        return 'Aman';
    }
}
