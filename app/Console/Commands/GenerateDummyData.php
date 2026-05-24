<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DummyDataService;
use App\Models\SensorReading;
use Carbon\Carbon;

class GenerateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:generate {--start=2025-01-01} {--force : Clear existing data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate realistic dummy data for sensor readings';

    /**
     * Execute the console command.
     */
    public function handle(DummyDataService $service)
    {
        if ($this->option('force')) {
            $this->info('Clearing existing sensor readings...');
            SensorReading::truncate();
        }

        $lastReading = SensorReading::orderBy('datetime', 'desc')->first();
        
        $startDate = $lastReading 
            ? Carbon::parse($lastReading->datetime)->addHour() 
            : Carbon::parse($this->option('start'));
            
        $endDate = Carbon::now();

        if ($startDate->greaterThan($endDate)) {
            $this->info('Data is already up to date.');
            return;
        }

        $this->info("Generating data from {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}...");
        
        $service->generateData($startDate, $endDate);

        $this->info('Data generation complete!');
    }
}
