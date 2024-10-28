<?php

namespace App\Http\Controllers;

use App\Services\GifService;
use Illuminate\Http\Request;
use App\Dtos\GifSearchDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Dtos\GetGifByIdDTO;
use App\Http\Requests\SearchGiftByWordRequest;
use App\Http\Requests\GetGifByIdRequest;
class GifController extends Controller
{
    protected $gifService;

    public function __construct(GifService $gifService)
    {
        $this->gifService = $gifService;
    }

    public function index(SearchGiftByWordRequest $request)
    {
        $request->validated();
    
        $limit = $request->query('limit');
        $offset = $request->query('offset');
        $query = $request->query('query');
        $normalizedQuery = preg_replace('/\s+/', ' ', trim(strtolower($query)));
    
        $gifSearchDTO = new GifSearchDTO($normalizedQuery, $limit, $offset);

        $data = $this->gifService->getAllData($gifSearchDTO);
        if (empty($data) || $data['total_count'] === 0) {
            return response()->json(['error' => 'gifs not found'], 404);
        }

        return response()->json($data, 200);
    }

    public function getGifById(GetGifByIdRequest $request)
    {
        $request->validated();

        $id = $request->route('id');
        
        $getGifBtIdDTO = new GetGifByIdDTO($id);

        $data = $this->gifService->getGifById($getGifBtIdDTO);
        if (empty($data)) {
            return response()->json(['error' => 'gifs not found'], 404);
        }

        return response()->json($data, 200);
    }
}
