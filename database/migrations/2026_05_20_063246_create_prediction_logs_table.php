<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prediction_logs', function (Blueprint $table) {
            $table->id();
            $table->json('input_data');
            $table->string('prediction');
            $table->json('probabilities');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prediction_logs');
    }
};