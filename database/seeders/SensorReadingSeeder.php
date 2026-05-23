<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorReading;
use Carbon\Carbon;

class SensorReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $records = [];
        
        for ($i = 0; $i < 24; $i++) {
            $tinggi_air = rand(100, 300) / 100; // Between 1.00 and 3.00
            
            $status = 'Aman';
            if ($tinggi_air > 5) {
                $status = 'Bahaya';
            } elseif ($tinggi_air > 3.5) {
                $status = 'Siaga';
            }

            $records[] = [
                'datetime' => $now->copy()->subHours(24 - $i)->format('Y-m-d H:i:s'),
                'tinggi_air' => $tinggi_air,
                'status_prediksi' => $status,
                'confidence' => rand(85, 99) / 100,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        SensorReading::insert($records);
    }
}
