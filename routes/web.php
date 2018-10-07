<?php

Route::group(['middleware' => 'auth.basic'], function () {
    Route::get('/', 'DashboardController@index');

    Route::post('temperature', 'UpdateTemperatureController');
});

Route::post('/webhook/github', 'GitHubWebhookController@gitRepoReceivedPush');

Route::ohDearWebhooks('/oh-dear-webhooks');
