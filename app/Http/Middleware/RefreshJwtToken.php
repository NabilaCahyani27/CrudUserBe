<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class RefreshJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (JWTAuth::parseToken()->authenticate()) {
            // Refresh token and set it in the response headers
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            $response = $next($request);
            $response->headers->set('Authorization', 'Bearer ' . $newToken);
            return $response;
        }

        return $next($request);
    }
}
