<?php

namespace App\Http\Controllers;

use App\Services\GifService;
use App\Dtos\GifSearchDTO;
use Illuminate\Support\Facades\Log;
use App\Dtos\GetGifByIdDTO;
use App\Dtos\SaveGifDTO;
use App\Http\Requests\SearchGiftByWordRequest;
use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SaveGifRequest;
use App\Exceptions\GifSaveException;
use Exception;
use Illuminate\Http\JsonResponse;

class GifController extends Controller
{
    protected $gifService;

    public function __construct(GifService $gifService)
    {
        $this->gifService = $gifService;
    }

    public function searchByWord(SearchGiftByWordRequest $request): JsonResponse
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

    public function getById(GetGifByIdRequest $request): JsonResponse
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

    public function saveGift(SaveGifRequest $request): JsonResponse
    {
        $request->validated();
        
        $saveGifDTO = new SaveGifDTO(
            $request->gif_id,
            $request->alias,
            $request->user_id,
        );

        try {
            $gif = $this->gifService->saveGif($saveGifDTO);
        } catch (GifSaveException $e) {
            Log::error('error in save gif: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),  // Mensaje de error específico
            ], 400);  // Código de error apropiado
        } catch (Exception $e) {
            Log::error('unexpected error: ' . $e->getMessage());

            return response()->json([
                'error' => 'an unexpected error occurred.',
            ], 500);
        }

        return response()->json([
            'message' => 'GIF saved successfully',
            'data' => $gif,
        ], 201);
    }
}
