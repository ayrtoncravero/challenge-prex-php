<?php

namespace App\Repositories;

use App\Models\Gif;
use App\Dtos\SaveGifDTO;
use Illuminate\Database\QueryException;
use RuntimeException;

class GifRepository
{
	protected $model;

    public function __construct(Gif $gif)
    {
        $this->model = $gif;
    }

	public function save(SaveGifDTO $saveGifDTO): Gif
    {
        try {
            return $this->model->create($saveGifDTO->toArray());
        } catch (QueryException $e) {
            throw new RuntimeException('database error occurred while saving GIF', 0, $e);
        } catch (\Exception $e) {
            throw new RuntimeException('unexpected error occurred while saving GIF', 0, $e);
        }
    }
}