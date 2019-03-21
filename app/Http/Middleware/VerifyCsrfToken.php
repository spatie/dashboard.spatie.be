<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    protected $except = [
        '/temperature',
        '/webhook/github',
        '/pusher/authenticate',
        '/oh-dear-webhooks',
        '/broadcasting/auth',
        '/laravel-websockets/auth',
    ];
}
