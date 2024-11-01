<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
