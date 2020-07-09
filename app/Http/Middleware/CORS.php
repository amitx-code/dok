<?php

namespace App\Http\Middleware;

class CORS
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'X-CSRF-Token');

        return $response;
    }

}
