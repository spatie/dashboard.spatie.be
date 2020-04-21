<?php

return [
    'tiles' => [
        'calendar' => [
            'ids' => [
                env('GOOGLE_CALENDAR_ID'),
            ]
        ],

        'twitter' => [
            'configurations' => [
                'default' => [
                    'access_token' => env('TWITTER_ACCESS_TOKEN'),
                    'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
                    'consumer_key' => env('TWITTER_CONSUMER_KEY'),
                    'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
                    'listen_for' => [
                        'spatie.be',
                        '@spatie_be',
                        'github.com/spatie',
                    ],
                ],
            ],
        ],

        'velo' => [
            'stations' => explode(',', env('VELO_STATIONS')),
        ]
    ],
];
