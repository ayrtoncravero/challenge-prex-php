<?php

namespace App\Dtos;

use Spatie\LaravelData\Data;

class SaveGifDTO extends Data
{
    public string $gif_id;
	public string $alias;
	public string $user_id;

    public function __construct(
        string $gif_id,
		string $alias,
		string $user_id,
    ) {
        $this->gif_id = $gif_id;
		$this->alias = $alias;
		$this->user_id = $user_id;
    }

	public function toArray(): array
    {
        return [
            'gif_id' => $this->gif_id,
            'alias' => $this->alias,
            'user_id' => $this->user_id,
        ];
    }
}