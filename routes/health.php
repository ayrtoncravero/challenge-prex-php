<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.environment'])->group(function () {
    Route::get('/health', [HealthController::class, 'index'])->name('health');
});