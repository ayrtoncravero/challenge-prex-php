<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogUserRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([LogUserRequest::class])->group(function () {
    Route::middleware('auth:api')->group(function () {
        require base_path('routes/gif.php');
        require base_path('routes/health.php');
    });

    require base_path('routes/oauth.php');
});

