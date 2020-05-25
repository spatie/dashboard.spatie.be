<?php

use App\Http\Middleware\AccessToken;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UpdateTemperatureController;
use App\Http\Controllers\UpdateIndoorAirQualityController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => AccessToken::class], function () {
    Route::view('/', 'dashboard');

    Route::post('indoor-air-quality', UpdateIndoorAirQualityController::class);
});

Route::ohDearWebhooks('/oh-dear-webhooks');
