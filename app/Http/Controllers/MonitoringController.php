<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Models\PredictionLog;
use App\Services\FloodPredictionService;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    protected $predictionService;
    protected $syncService;

    public function __construct(FloodPredictionService $predictionService, \App\Services\ExcelSyncService $syncService)
    {
        $this->predictionService = $predictionService;
        $this->syncService = $syncService;
    }

    public function monitoringSistem(Request $request)
    {
        // Sync from Excel before showing monitoring
        $this->syncService->syncFromExcel();
        
        $readings = SensorReading::orderBy('datetime', 'desc')->paginate(10);
        return view('monitoring', compact('readings'));
    }

    public function grafikTinggiAir(Request $request)
    {
        $days = $request->input('days');
        $month = $request->input('month');
        $year = $request->input('year', Carbon::now()->year);
        $isSearching = $request->has('search');

        $chartLabels = [];
        $chartData = [];
        $title = "Visualisasi Data";

        if ($isSearching) {
            $query = SensorReading::query();

            if ($month) {
                $query->whereMonth('datetime', $month)
                      ->whereYear('datetime', $year);
                $title = Carbon::create($year, $month)->translatedFormat('F Y');
                $format = 'd M H:i';
            } elseif ($days) {
                $query->where('datetime', '>=', Carbon::now()->subDays($days));
                $title = "$days Hari Terakhir";
                $format = $days <= 1 ? 'H:i' : 'd M H:i';
            } else {
                // If search button clicked but nothing selected, default to 7 days or handle accordingly
                $query->where('datetime', '>=', Carbon::now()->subDays(7));
                $title = "7 Hari Terakhir";
                $format = 'd M H:i';
                $days = 7;
            }

            $readings = $query->orderBy('datetime', 'asc')->get();
            $chartLabels = $readings->pluck('datetime')->map(function ($date) use ($format) {
                return $date->format($format);
            });
            $chartData = $readings->pluck('tinggi_air');
        }

        return view('grafik', compact('chartLabels', 'chartData', 'days', 'month', 'year', 'title', 'isSearching'));
    }

    public function historiData(Request $request)
    {
        $query = SensorReading::query();

        if ($request->filled('status')) {
            $query->where('status_prediksi', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('datetime', $request->date);
        }

        $readings = $query->orderBy('datetime', 'desc')->paginate(15)->withQueryString();

        return view('histori', compact('readings'));
    }

    public function prediksiBanjir(Request $request)
    {
        $latestReadings = SensorReading::orderBy('datetime', 'desc')->take(24)->get();
        return view('prediksi', compact('latestReadings'));
    }

    public function prosesPrediksi(Request $request)
    {
        $request->validate([
            'tinggi_air' => 'nullable|numeric|min:0'
        ]);

        try {
            // Get sequence of 24 latest readings from database (Cara Lama)
            $readings = SensorReading::orderBy('datetime', 'desc')->take(24)->get()->reverse();
            
            $sequence = $readings->pluck('tinggi_air')->toArray();

            // If user provided manual input, use it as the latest value
            if ($request->filled('tinggi_air')) {
                if (count($sequence) >= 24) {
                    array_shift($sequence);
                }
                $sequence[] = (float) $request->tinggi_air;
            }

            if (count($sequence) < 1) {
                return back()->with('error', 'Data tidak cukup untuk melakukan prediksi.');
            }

            // Call the standard /predict endpoint
            $result = $this->predictionService->getPrediction($sequence);

            $prediction = $result['prediction'];
            $confidence = $result['confidence'];
            
            $color = 'green';
            if ($prediction === 'Siaga') $color = 'yellow';
            if ($prediction === 'Bahaya') $color = 'red';

            return back()->with('success', "
                <div class='flex flex-col items-center'>
                    <span class='text-lg font-semibold text-slate-800'>Hasil Prediksi: <span class='text-{$color}-600 dark:text-{$color}-400'>{$prediction}</span></span>
                    <span class='text-sm mt-1 text-slate-500 font-medium'>Confidence Level: " . number_format($confidence * 100, 1) . "%</span>
                    <p class='text-[10px] mt-2 text-slate-400 italic font-bold uppercase tracking-wider'>Sumber Data: Database Sensor</p>
                </div>
            ");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses prediksi: ' . $e->getMessage());
        }
    }
}
