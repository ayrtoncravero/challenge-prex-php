<?php

namespace App\Http\Middleware;

use Closure;

class CheckEnvironment
{
    public function handle($request, Closure $next)
    {
        $environment = config('app.env');

        if ($environment !== 'develop') {
            return response()->json([
				'error' => [
					'code' => 404,
					'message' => 'the resource you are looking for does not exist'
				]
			], 404);
        }

        return $next($request);
    }
}