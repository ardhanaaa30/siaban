<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\PredictionLog;
use App\Services\FloodPredictionService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $syncService;
    protected $predictionService;

    public function __construct(\App\Services\ExcelSyncService $syncService, FloodPredictionService $predictionService)
    {
        $this->syncService = $syncService;
        $this->predictionService = $predictionService;
    }

    public function index()
    {
        // Sync from Excel first to ensure dashboard is not empty
        $this->syncService->syncFromExcel();

        $latestReading = SensorReading::orderBy('datetime', 'desc')->first();
        $highestToday = SensorReading::whereDate('datetime', Carbon::today())->max('tinggi_air');
        $readingsToday = SensorReading::whereDate('datetime', Carbon::today())->orderBy('datetime', 'asc')->get();
        
        // If today is empty, fallback to last 24 hours of data for the chart
        if ($readingsToday->isEmpty()) {
            $readingsToday = SensorReading::orderBy('datetime', 'desc')->take(24)->get()->reverse();
            $highestToday = SensorReading::max('tinggi_air'); // Overall max if today is empty
        }

        $chartLabels = $readingsToday->pluck('datetime')->map(function ($date) {
            return $date->format('H:i');
        });
        $chartData = $readingsToday->pluck('tinggi_air');
        
        // Latest prediction logic same as Prediksi page (Database sequence)
        $readings = SensorReading::orderBy('datetime', 'desc')->take(24)->get()->reverse();
        $sequence = $readings->pluck('tinggi_air')->toArray();
        
        $latestPrediction = null;
        if (!empty($sequence)) {
            try {
                $latestPrediction = (object) $this->predictionService->getPrediction($sequence);
                // Convert created_at for view consistency
                $latestPrediction->created_at = Carbon::now();
            } catch (\Exception $e) {
                // Fallback to database log if API fails
                $latestPrediction = PredictionLog::orderBy('created_at', 'desc')->first();
            }
        }
        
        // Count alerts today
        $alertCount = SensorReading::whereDate('datetime', Carbon::today())
            ->whereIn('status_prediksi', ['Siaga', 'Bahaya'])
            ->count();

        return view('dashboard', compact(
            'latestReading', 
            'highestToday', 
            'chartLabels', 
            'chartData', 
            'latestPrediction',
            'alertCount'
        ));
    }
}
