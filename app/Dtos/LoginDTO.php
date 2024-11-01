<?php

namespace App\Dtos;

use Spatie\LaravelData\Data;

class LoginDTO extends Data
{
    public string $email;
    public string $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = $email;
        $this->password = $password;
    }
}