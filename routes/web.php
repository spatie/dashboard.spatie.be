<?php

use App\Http\Middleware\AccessToken;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\GitHubWebhookController;
use App\Http\Controllers\UpdateTemperatureController;
use App\Http\Controllers\PartyController;

Route::group(['middleware' => AccessToken::class], function () {
    Route::get('/', DashboardController::class);

    Route::post('temperature', UpdateTemperatureController::class);

    Route::get('/staff', StaffController::class);

    Route::get('/party', [PartyController::class, 'index']);
    Route::post('/party', [PartyController::class, 'store']);
});

Route::post('/webhook/github', [GitHubWebhookController::class, 'gitRepoReceivedPush']);

Route::ohDearWebhooks('/oh-dear-webhooks');
