<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'input_data',
        'prediction',
        'probabilities',
    ];

    protected $casts = [
        'input_data' => 'json',
        'probabilities' => 'json',
    ];
}
