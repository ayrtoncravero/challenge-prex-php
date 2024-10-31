<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}
