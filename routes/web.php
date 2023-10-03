<?php

use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Route;

Route::view('apple-music-token', 'apple-music-token');

Route::middleware(AccessToken::class)->group(function () {
    Route::view('/', 'dashboard');
});

Route::ohDearWebhooks('/oh-dear-webhooks');
