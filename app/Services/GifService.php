<?php

namespace App\Services;

use App\Repositories\MyRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\dtos\GifSearchDTO;
use App\Dtos\GetGifByIdDTO;

class GifService
{
    protected $myRepository;

    // public function __construct(MyRepository $myRepository)
    // {
    //     $this->myRepository = $myRepository;
    // }

    public function getAllData(GifSearchDTO $gifSearchDTO)
    {
		// TODO: Guardar la base url de giphy en el env y siempre usarla para cada consulta

		// TODO: Este client deberia de sacarlo a una funcion que lo devualva o usar un valor global 
		$client = new Client();

		$url = 'https://api.giphy.com/v1/gifs/search';
        $apiKey = "nA1zZdTjYL2GZ4OPF1GYyJwDspndR1WY";

		try {
			// TODO: Hacer una abstracción de la libreria para hacer consultas http y pasarle los parametos
			// token y demas para las consultas y los demas campos opcioneles como el limit
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

			// return $data;

			// Hacer un guardado de la respuesta con los datos del usuario, incluyendo su IP(revisar si lo recomendado es encriptar su ip)

        	return $this->formatResponseSearchGiftByWord($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los GIFs'], 500);
        }
    }

	public function getGifById(GetGifByIdDTO $getGifByIdDTO){
		$client = new Client();

		$url = "api.giphy.com/v1/gifs/{$getGifByIdDTO->id}";
        $apiKey = "nA1zZdTjYL2GZ4OPF1GYyJwDspndR1WY";

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
			'message' => 'GIF encontrado correctamente.',
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
				'message' => 'GIF encontrado correctamente.',
			];
		}
    }
}