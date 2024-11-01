<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserRequestLog;
use Illuminate\Support\Facades\Crypt;

class LogUserRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la IP real del cliente considerando proxies
        $ipAddress = Crypt::encryptString($request->header('X-Forwarded-For') ?: $request->ip());

        $user = $request->user();

        $requestBody = $request->getContent();

        $response = $next($request);

        $statusCode = $response->getStatusCode();

        $responseBody = $response->getContent();

        UserRequestLog::create([
            'user_id' => $user ? $user->id : null,
            'endpoint' => $request->fullUrl(), 
            'request_body' => $requestBody,
            'response_code' => $statusCode,
            'response_body' => $responseBody,
            'ip_address' => $ipAddress,
        ]);

        return $response;
    }
}
