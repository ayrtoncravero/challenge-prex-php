<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use App\Dtos\LoginDTO;
use Laravel\Passport\Client;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $user = Auth::user();

        $tokenResult = $user->createToken('user_access_token')->accessToken;
        return response()->json(['token' => $tokenResult], 200);
    }

    public function register(RegisterUserRequest $request)
    {
        $request->validated();

        Log::info('Validación exitosa.');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->accessToken;

        Log::info('Generated Token:', ['token' => $token]);

        return response()->json(['token' => $token], 201);
    }
}
