<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // All authenticated users can access dashboard and grafik
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/grafik', [\App\Http\Controllers\MonitoringController::class, 'grafikTinggiAir'])->name('grafik');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Suggestions store route - accessible to everyone
Route::post('/saran', [\App\Http\Controllers\SuggestionController::class, 'store'])->name('suggestions.store');

// Admin and Staff routes
Route::middleware(['auth', 'role:Admin,Staff'])->group(function () {
    Route::get('/prediksi', [\App\Http\Controllers\MonitoringController::class, 'prediksiBanjir'])->name('prediksi');
    Route::post('/prediksi', [\App\Http\Controllers\MonitoringController::class, 'prosesPrediksi'])->name('prediksi.proses'); // Placeholder for Phase 7

    Route::get('/saran', [\App\Http\Controllers\SuggestionController::class, 'index'])->name('suggestions.index');
});

// Admin ONLY routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/monitoring', [\App\Http\Controllers\MonitoringController::class, 'monitoringSistem'])->name('monitoring');
    
    Route::get('/histori', [\App\Http\Controllers\MonitoringController::class, 'historiData'])->name('histori');
    
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('users.update-role');

    Route::get('/reset-requests', [\App\Http\Controllers\UserController::class, 'resetRequests'])->name('users.reset-requests');
    Route::post('/reset-requests/{resetRequest}/approve', [\App\Http\Controllers\UserController::class, 'approveReset'])->name('users.reset-requests.approve');
    Route::post('/reset-requests/{resetRequest}/reject', [\App\Http\Controllers\UserController::class, 'rejectReset'])->name('users.reset-requests.reject');
});

require __DIR__.'/auth.php';
