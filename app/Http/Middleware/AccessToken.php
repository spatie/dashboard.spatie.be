<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class AccessToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        if ($request->get('access-token') === config('app.access_token')) {
            return $next($request);
        }

        abort(401);
    }
}
