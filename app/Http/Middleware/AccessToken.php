<?php

namespace App\Http\Middleware;

use Closure;

class AccessToken
{
    public function handle($request, Closure $next)
    {
        if ($request->get('access-token') !== config('app.access_token')) {
            abort(401);
        }

        return $next($request);
    }
}
