<?php

namespace App\Dtos;

use Spatie\LaravelData\Data;

class GetGifByIdDTO extends Data
{
    public string $id;

    public function __construct(
        string $id,
    ) {
        $this->id = $id;
    }
}