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

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        // TODO: Agregar archivo dedicado de validacion como en gift
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $loginDTO = new LoginDTO($request->email, $request->password);

        $response = $this->authService->login($loginDTO);

        // // Intentar autenticar al usuario
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     // Obtener el usuario autenticado
        //     $user = Auth::user();

        //     // Generar el token
        //     $accessToken = $user->createToken('password_grant_access_token', ['view-account', 'edit-account'])->accessToken;

        //     // Responder con el token
        //     return response()->json(['token' => $token], 200);
        // }

        // return response()->json(['message' => 'Credenciales incorrectas.'], 401);
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
