<?php

return [
    'name' => 'Dashboard',
    'access_token' => env('ACCESS_TOKEN'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://dashboard.spatie.be'),
    'timezone' => 'Europe/Brussels',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'maintenance' => [
        'driver' => 'file',
    ],
];
