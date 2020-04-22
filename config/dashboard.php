<?php

return [
    'theme' => 'dark',

    'auto_theme_location' => [
        'lat' => 51.260197,
        'lng' => 4.402771,
    ],

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
        ],

        'belgian_trains' => [
            [
                'departure' => 'Antwerpen-Centraal',
                'destination' => 'Gent-Dampoort',
                'label' => 'Gent',
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
            [
                'departure' => 'Antwerpen-Centraal',
                'destination' => 'Kapellen',
                'label' => 'Kapellen',
            ],
        ],
    ],
];
