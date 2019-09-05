<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'buienradar' => [
        'latitude' => env('BUIENRADAR_LATITUDE'),
        'longitude' => env('BUIENRADAR_LONGITUDE'),
    ],
    'forecast' => [
        'account_id' => env('FORECAST_ACCOUNT_ID'),
        'token' => env('FORECAST_TOKEN'),
        'people' => env('FORECAST_PEOPLE'),
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'hook_secret' => env('GITHUB_HOOK_SECRET'),
        'username' => env('GITHUB_USERNAME'),
    ],

    'last-fm' => [
        'api_key' => env('LAST_FM_API_KEY'),
        'users' => explode(',', env('LAST_FM_USERS')),
    ],

    'open_weather_map' => [
        'key' => env('OPEN_WEATHER_MAP_KEY'),
    ],

    'packagist' => [
        'vendor' => env('PACKAGIST_VENDOR'),
    ],
    'slack' => [
        'app_token' => env('SLACK_APP_TOKEN'),
    ],
    'train_connections' => [
        [
            'departure' => 'Antwerpen-Centraal',
            'destination' => 'Gent-Dampoort',
            'label' => 'Gent'
        ],
        [
            'departure' => 'Antwerpen-Centraal',
            'destination' => 'Mechelen',
            'label' => 'Mechelen',
        ],
        [
            'departure' => 'Antwerpen-Centraal',
            'destination' => 'Overpelt',
            'label' => 'Overpelt',
        ],
    ],
    'velo' => [
        'stations' => explode(',', env('VELO_STATIONS')),
    ],

];
