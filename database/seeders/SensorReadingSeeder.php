<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\DummyDataService;
use Carbon\Carbon;

class SensorReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DummyDataService $service): void
    {
        $startDate = Carbon::parse('2025-01-01');
        $endDate = Carbon::now();
        
        $service->generateData($startDate, $endDate);
    }
}
