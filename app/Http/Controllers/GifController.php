<?php

namespace App\Http\Controllers;

use App\Services\GifService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class GifController extends Controller
{
    protected $gifService;

    public function __construct(GifService $gifService)
    {
        $this->gifService = $gifService;
    }

    public function index(Request $request)
    {
        $queryParam = $request->query('query') ?? 'gandalf';
        $limitParam = $request->query('limit') ?? 10;
        $offsetParam = $request->query('offset') ?? 0;

        $normalizedQuery = preg_replace('/\s+/', ' ', trim(strtolower($queryParam)));

        $data = $this->gifService->getAllData($normalizedQuery, $limitParam, $offsetParam);
        if (empty($data) || $data['total_count'] === 0) {
            return response()->json(['error' => 'gifs not found'], 404);
        }

        return response()->json($data, 200);
    }
}
