<?php

use App\Http\Middleware\AccessToken;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => AccessToken::class], function () {
    Route::view('/', 'dashboard');
});

Route::ohDearWebhooks('/oh-dear-webhooks');
