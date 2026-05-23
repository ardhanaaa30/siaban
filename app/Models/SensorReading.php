<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'tinggi_air',
        'status_prediksi',
        'confidence',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'tinggi_air' => 'float',
        'confidence' => 'float',
    ];
}
