<?php

namespace App\Http\Middleware;

use Closure;

class AccessToken
{
    public function handle($request, Closure $next)
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
