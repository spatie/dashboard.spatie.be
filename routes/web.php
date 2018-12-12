<?php

use App\Http\Middleware\AccessToken;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GitHubWebhookController;
use App\Http\Controllers\UpdateTemperatureController;

Route::group(['middleware' => AccessToken::class], function () {
    Route::get('/', DashboardController::class);

    Route::post('temperature', UpdateTemperatureController::class);

    Route::get('/staff', StaffController::class);
});

Route::post('/webhook/github', [GitHubWebhookController::class, 'gitRepoReceivedPush']);

Route::ohDearWebhooks('/oh-dear-webhooks');
