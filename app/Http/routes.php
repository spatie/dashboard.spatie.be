<?php

Route::group(['middleware' => 'auth.very_basic'], function () {
    Route::get('/', 'DashboardController@index');
    Route::post('/pusher/authenticate', 'PusherController@authenticate');
});

Route::post('/webhook/github', 'GitHubWebhookController@gitRepoReceivedPush');
