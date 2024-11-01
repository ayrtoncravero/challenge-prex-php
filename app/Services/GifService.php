<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\dtos\GifSearchDTO;
use App\Dtos\GetGifByIdDTO;
use App\Dtos\SaveGifDTO;
use App\Repositories\GifRepository;
use App\Models\Gif;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use RuntimeException;
use App\Exceptions\GifSaveException;

class GifService
{
    protected $gifRepository;
    protected $apiUrl;

    public function __construct(GifRepository $gifRepository)
    {
        $this->gifRepository = $gifRepository;
        $this->apiUrl = config('app.custom_secrets.giphy_base_url');
    }

    public function getAllData(GifSearchDTO $gifSearchDTO)
    {
		$client = new Client();

		$url = "$this->apiUrl/search";
        $apiKey = config('app.custom_secrets.giphy_api_key');

		try {
            $response = $client->request('GET', $url, [
                'query' => [
                    'api_key' => $apiKey,
					'q' => $gifSearchDTO->query,
                    'limit' => $gifSearchDTO->limit,
					'offset' => $gifSearchDTO->offset,
                    'rating' => 'g',
                ]
            ]);

			$data = json_decode($response->getBody(), true);

        	return $this->formatResponseSearchGiftByWord($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error to get gifs data'], 500);
        }
    }

	public function getGifById(GetGifByIdDTO $getGifByIdDTO){
		$client = new Client();

        $url = "$this->apiUrl/{$getGifByIdDTO->id}";
        $apiKey = config('app.custom_secrets.giphy_api_key');

		try {
            $response = $client->request('GET', $url, [
                'query' => [
                    'api_key' => $apiKey,
                ]
            ]);

			$data = json_decode($response->getBody(), true);

        	return $this->formatResponseSearchById($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error in get gif by id'], 500);
        }
	}

	private function formatResponseSearchGiftByWord($data)
    {
        $formattedData = [
			'success' => true,
            'total_count' => $data['pagination']['total_count'],
            'count' => $data['pagination']['count'],
            'offset' => $data['pagination']['offset'],
            'gifs' => [],
			'message' => 'gif found successfully',
        ];

        foreach ($data['data'] as $gif) {
            $formattedData['gifs'][] = [
                'id' => $gif['id'],
                'url' => $gif['url'],
                'title' => $gif['title'],
                'username' => $gif['username'] ?? 'unknown',
                'images' => [
                    'original' => $gif['images']['original']['url'] ?? null,
                    'fixed_height' => $gif['images']['fixed_height']['url'] ?? null,
                ],
            ];
        }

        return $formattedData;
    }

	protected function formatResponseSearchById($data)
    {
        if (isset($data['data'])) {
			return [
				'success' => true,
				'data' => [
					'id' => $data['data']['id'],
					'title' => $data['data']['title'],
					'url' => $data['data']['url'],
				],
				'message' => 'gif found successfully',
			];
		}
    }

    public function saveGif(SaveGifDTO $saveGifDTO): Gif
    {
        try {
            return $this->gifRepository->save($saveGifDTO);
        } catch (QueryException $e) {
            Log::error('database error while saving gif: ' . $e->getMessage(), ['data' => $saveGifDTO->toArray()]);
            throw new GifSaveException('there was an error saving the gif', 0, $e);
        } catch (\Exception $e) {
            Log::error('unexpected error while saving gif: ' . $e->getMessage(), ['data' => $saveGifDTO->toArray()]);
            throw new RuntimeException('an unexpected error occurred while saving the gif', 0, $e);
        }
    }
}