<?php

use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;

// TODO: Cambiar nombre de este endpoint
Route::get('/search/gift', [GifController::class, 'index']);
Route::get('/gift/{id}', [GifController::class, 'getGifById']);