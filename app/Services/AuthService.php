<?php

namespace App\Services;

use App\Dtos\LoginDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class AuthService
{
	public function login(LoginDTO $loginDto)
    {
		$data = [
			'email' => $loginDto->email,
        	'password' => $loginDto->password,
		];
		
		if (!Auth::attempt($data)) {
			return [
				'status' => 401,
                'body' => json_encode(['message' => 'Unauthorized'])
            ];
        }

		$user = Auth::user();

		$newUser = new User();
		$newUser->email = $loginDto->email;
		$newUser->password = $loginDto->password;

		
		$token = $newUser->createToken('user_access_token')->accessToken;

		dd($token);

		// $token = $user->createToken('user_access_token')->accessToken;
	}
}