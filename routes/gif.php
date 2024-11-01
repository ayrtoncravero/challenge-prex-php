<?php

use App\Http\Controllers\GifController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserRequest;

Route::middleware([LogUserRequest::class])->group(function () {
	Route::get('/gif', [GifController::class, 'searchByWord'])->name('searchByWord');
	Route::get('/gif/{id}', [GifController::class, 'getById'])->name('getById');
	Route::post('/gif', [GifController::class, 'saveGift'])->name('saveGift');
});