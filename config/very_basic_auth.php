<?php

    /**
     * Configuration for the "HTTP Very Basic Auth"-middleware
     */
    return [
        // Username
        'user'              => env('BASIC_AUTH_USERNAME'),

        // Password
        'password'          => env('BASIC_AUTH_PASSWORD'),

        // Environments where the middleware is active
        'envs'              => [
            'local',
            'dev',
            'development',
            'staging',
            'production',
            'testing'
        ],

        // Message to display if the user "opts out"/clicks "cancel"
        'error_message'     => 'You have to supply your credentials to access this resource.'
    ];
