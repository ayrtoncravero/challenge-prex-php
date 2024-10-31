<?php

use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserRequest;

// TODO: cumplir el restful solo hay que dejr el /gift
Route::middleware([LogUserRequest::class])->group(function () {
	Route::get('/search/gift', [GifController::class, 'searchByWord']);
	Route::get('/gift/{id}', [GifController::class, 'getById']);
	Route::post('/gift', [GifController::class, 'saveGift']);
});