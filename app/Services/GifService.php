<?php

namespace App\Services;

use App\Repositories\MyRepository;
use GuzzleHttp\Client;

class GifService
{
    protected $myRepository;

    // public function __construct(MyRepository $myRepository)
    // {
    //     $this->myRepository = $myRepository;
    // }

    public function getAllData($normalizedQuery, $limitParam, $offsetParam)
    {
		// TODO: Guardar la base url de giphy en el env y siempre usarla para cada consulta

		$client = new Client();

		$url = 'https://api.giphy.com/v1/gifs/search';
        $apiKey = env('GIPHY_API_KEY');

		try {
            $response = $client->request('GET', $url, [
                'query' => [
                    'api_key' => $apiKey,
					'q' => $normalizedQuery,
                    'limit' => $limitParam,
					'offset' => $offsetParam,
                    'rating' => 'g',
                ]
            ]);

			$data = json_decode($response->getBody(), true);

			// Hacer un guardado de la respuesta con los datos del usuario, incluyendo su IP(revisar si lo recomendado es encriptar su ip)

        	return $this->formatResponse($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los GIFs'], 500);
        }
    }

	private function formatResponse($data)
    {
        $formattedData = [
            'total_count' => $data['pagination']['total_count'],
            'count' => $data['pagination']['count'],
            'offset' => $data['pagination']['offset'],
            'gifs' => [],
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
}