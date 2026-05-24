<?php

namespace App\Services;

use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDataService
{
    private $thresholdAman = 3.5;
    private $thresholdBahaya = 5.0;
    private $deltaSiagaTrigger = 0.3;

    public function generateData(Carbon $startDate, Carbon $endDate)
    {
        $baseDate = Carbon::parse('2025-01-01');
        $startHourIndex = $baseDate->diffInHours($startDate);
        $totalHours = $startDate->diffInHours($endDate);
        
        if ($totalHours <= 0) return;

        $waterLevels = $this->generateFloodEvents($totalHours + 1, $startHourIndex);

        $records = [];
        // Get the very last reading to calculate the first delta
        $lastReading = SensorReading::orderBy('datetime', 'desc')->first();
        $previousTinggi = $lastReading ? $lastReading->tinggi_air : null;

        foreach (range(0, $totalHours) as $i) {
            $currentDate = $startDate->copy()->addHours($i);
            $tinggiAir = round($waterLevels[$i], 2);
            
            $delta = $previousTinggi !== null ? round($tinggiAir - $previousTinggi, 3) : 0;
            $status = $this->calculateStatus($tinggiAir, $delta);
            
            $records[] = [
                'datetime' => $currentDate->format('Y-m-d H:i:s'),
                'tinggi_air' => $tinggiAir,
                'status_prediksi' => $status,
                'confidence' => rand(85, 99) / 100,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $previousTinggi = $tinggiAir;

            if (count($records) >= 1000) {
                SensorReading::insert($records);
                $records = [];
            }
        }

        if (count($records) > 0) {
            SensorReading::insert($records);
        }
    }

    private function generateFloodEvents($length, $startHourIndex = 0)
    {
        $waterLevel = [];
        for ($i = 0; $i < $length; $i++) {
            $t = $startHourIndex + $i;
            // Base level: sinusoidal (monthly and daily)
            $base = 2.5 + 0.4 * sin(2 * M_PI * $t / (24 * 30)) + 0.1 * sin(2 * M_PI * $t / 24);
            $waterLevel[$i] = $base;
        }

        // Random flood events
        $numMonths = $length / (24 * 30);
        $numEvents = rand(max(1, floor(5 * $numMonths)), ceil(7 * $numMonths));
        
        if ($numEvents > 0) {
            $eventStarts = [];
            for ($i = 0; $i < $numEvents; $i++) {
                $eventStarts[] = rand(0, $length - 1);
            }
            sort($eventStarts);

            foreach ($eventStarts as $startIdx) {
                $duration = rand(30, 100);
                $peakHeight = (rand(200, 400) / 100); 
                
                $peakPos = $duration * 0.2;
                $sigmaRise = $peakPos / 1.5;
                $sigmaFall = ($duration - $peakPos) / 2.2;

                for ($et = 0; $et < $duration; $et++) {
                    $idx = $startIdx + $et;
                    if ($idx >= $length) break;

                    if ($et < $peakPos) {
                        $peakShape = exp(-(pow($et - $peakPos, 2)) / (2 * pow($sigmaRise, 2)));
                    } else {
                        $peakShape = exp(-(pow($et - $peakPos, 2)) / (2 * pow($sigmaFall, 2)));
                    }

                    $waterLevel[$idx] += $peakShape * $peakHeight;
                }
            }
        }

        // Add noise
        for ($t = 0; $t < $length; $t++) {
            $noise = (rand(-50, 50) / 1000); // approx N(0, 0.05)
            $waterLevel[$t] += $noise;
            $waterLevel[$t] = min($waterLevel[$t], 7.0);
        }

        // Simple moving average for smoothing (window size 3)
        $smoothed = [];
        for ($t = 0; $t < $length; $t++) {
            $sum = $waterLevel[$t];
            $count = 1;
            if ($t > 0) {
                $sum += $waterLevel[$t-1];
                $count++;
            }
            if ($t < $length - 1) {
                $sum += $waterLevel[$t+1];
                $count++;
            }
            $smoothed[$t] = $sum / $count;
        }

        return $smoothed;
    }

    private function calculateStatus($val, $delta)
    {
        if ($val >= $this->thresholdBahaya) {
            return 'Bahaya';
        } elseif ($val >= $this->thresholdAman || $delta >= $this->deltaSiagaTrigger) {
            return 'Siaga';
        } else {
            return 'Aman';
        }
    }
}
