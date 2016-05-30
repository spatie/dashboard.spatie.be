<?php

Route::group(['middleware' => 'auth.very_basic'], function () {

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::post('/pusher/authenticate', 'PusherController@authenticate');

});

Route::post('/webhook/github', 'GitHubWebhookController@gitRepoReceivedPush');
