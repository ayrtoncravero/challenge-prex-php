<?php

use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;

Route::get('/search/gift', [GifController::class, 'index']);