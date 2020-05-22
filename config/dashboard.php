<?php

return [
    /*
     * The dashboard supports these themes:
     *
     * - light: always use light mode
     * - dark: always use dark mode
     * - device: follow the OS preference for determining light or dark mode
     * - auto: use light mode when the sun is up, dark mode when the sun is down
     */
    'theme' => 'light',

    /*
     * When the dashboard uses the `auto` theme, these coordinates will be used
     * to determine whether the sun is up or down
     */
    'auto_theme_location' => [
        'lat' => 51.260197,
        'lng' => 4.402771,
    ],

    'tiles' => [
        'calendar' => [
            'ids' => [
                env('GOOGLE_CALENDAR_ID'),
            ],
            'refresh_interval_in_seconds' => 3,
        ],

        'twitter' => [
            'configurations' => [
                'default' => [
                    'access_token' => env('TWITTER_ACCESS_TOKEN'),
                    'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
                    'consumer_key' => env('TWITTER_CONSUMER_KEY'),
                    'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
                    'listen_for' => [
                        '@spatie_be',
                        'spatie.be',
                        'github.com/spatie'
                    ],
                ],
            ],
            'refresh_interval_in_seconds' => 3,
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

        'time_weather' => [
            'open_weather_map_key' => env('OPEN_WEATHER_MAP_KEY'),
            'open_weather_map_city' => 'Antwerp',
            'buienradar_latitude' => env('BUIENRADAR_LATITUDE'),
            'buienradar_longitude' => env('BUIENRADAR_LONGITUDE'),
        ],
    ],
];
