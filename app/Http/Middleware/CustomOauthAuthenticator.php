<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

class CustomOauthAuthenticator
{

    /**
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
		$token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $auth = app('auth');

        $user = $auth->guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!$this->isValidToken($user, $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Auth::setUser($user);

        return $next($request);



		// // Obtener el token de la cabecera Authorization
		// $token = $request->bearerToken();
    
		// // Verificar si el token está presente
		// if (!$token) {
		// 	return response()->json(['error' => 'Unauthorized'], 401);
		// }
	
		// // Obtener el usuario autenticado mediante el guard 'api'
		// $auth = app('auth');
		// // $oauthClient = Client::where('user_id', '=', $user->id)->first();

		// // $attempt = Auth::attempt(["email" => $request->input('email'), "password" => $request->input('password')]);
		// $user = $auth->guard('api')->user();
		// dd($user);
	
		// // Verificar si el usuario está autenticado
		// if (!$user) {
		// 	return response()->json(['error' => 'Unauthorized'], 401);
		// }
	
		// // Validar el token
		// if (!$this->isValidToken($user, $token)) {
		// 	return response()->json(['error' => 'Unauthorized'], 401);
		// }
	
		// // Establecer el usuario en el contexto de autenticación
		// Auth::setUser($user);
	
		// // Pasar al siguiente middleware/controlador
		// return $next($request);
    }

    /**
     * @param User $user
     * @param string $token
     * @return bool
     */
    protected function isValidToken(User $user, string $token): bool
    {
		// $publicKey = config('passport.public_key');
		$publicKey = file_get_contents(storage_path('oauth-public.key'));
        $tokens = $user->tokens;

        foreach ($tokens as $userToken) {
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            $expirationDate = new Carbon($decoded->exp);
            if ($userToken->id == $decoded->jti && !$userToken->revoked && $expirationDate->toDate() > now()) {
                return true;
            }
        }


        return false;
    }
}