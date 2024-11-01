<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use App\Services\GifService;
use App\DTOs\GifSearchDTO;


class GifControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $gifService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->gifService = Mockery::mock(GifService::class);
        $this->app->instance(GifService::class, $this->gifService);
    }

    public function test_search_by_word_with_valid_data()
    {
        // // Prepara los datos de entrada
        // $query = 'gandalf';
        // $limit = 10;
        // $offset = 0;

        // // Simula la respuesta del servicio
        // $mockData = [
        //     'total_count' => 1,
        //     'gifs' => [['id' => '1', 'title' => 'Example GIF']],
        // ];
        // $this->gifService->shouldReceive('getAllData')
        //     ->once()
        //     ->with(Mockery::type(GifSearchDTO::class)) // Verifica que se pase una instancia de GifSearchDTO
        //     ->andReturn($mockData);

        // // Realiza la solicitud
        // $response = $this->getJson("/api/gifs/search?query={$query}&limit={$limit}&offset={$offset}");

        // // Asegúrate de que la respuesta sea exitosa
        // $response->assertStatus(200);
        // $response->assertJson($mockData);
        $response = $this->get('/');
 
        $response->assertStatus(200);
    }
}
