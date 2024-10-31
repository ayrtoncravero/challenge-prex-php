<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['check.environment'])->get('/health', function () {
    Route::get('/health', [HealthController::class, 'index']);
});