<?php

namespace App\Dtos;

use Spatie\LaravelData\Data;

class GifSearchDTO extends Data
{
    public string $query;
    public int $limit;
    public int $offset;

    public function __construct(
        string $query,
        int $limit = 10,
        int $offset = 0
    ) {
        $this->query = $query;
        $this->limit = $limit;
        $this->offset = $offset;
    }
}