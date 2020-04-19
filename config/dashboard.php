<?php

return [
    'tiles' => [
        'calendar' => [
            'ids' => [
                env('GOOGLE_CALENDAR_ID'),
            ]
        ],

        'twitter' => [
            'connections' => [
                'default' => [
                    'access_token' => env('TWITTER_ACCESS_TOKEN'),
                    'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
                    'consumer_key' => env('TWITTER_CONSUMER_KEY'),
                    'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
                    'listenFor' => [
                        'spatie.be',
                        '@spatie_be',
                        'github.com/spatie',
                    ],
                ],
            ],
        ],
    ],
];
