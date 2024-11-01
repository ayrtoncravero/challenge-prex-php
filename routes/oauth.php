<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\LogUserRequest;

Route::middleware([LogUserRequest::class])->group(function () {
	Route::post('/login', [AuthController::class, 'login'])->name('login');
	Route::post('/register', [AuthController::class, 'register'])->name('register');
});