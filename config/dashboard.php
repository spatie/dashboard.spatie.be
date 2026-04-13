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
    'theme' => 'auto',

    /*
     * When the dashboard uses the `auto` theme, these coordinates will be used
     * to determine whether the sun is up or down
     */
    'auto_theme_location' => [
        'lat' => 51.260197,
        'lng' => 4.402771,
    ],

    /*
     * ISO-8601 weekday number, using the configured timezone.
     * Monday = 1, Sunday = 7.
     */
    'weekplanning' => [
        'day_of_week' => (int) env('WEEKPLANNING_DAY_OF_WEEK', 1),
        'start_time' => env('WEEKPLANNING_START_TIME', '12:00'),
        'end_time' => env('WEEKPLANNING_END_TIME', '12:15'),
        'timezone' => env('WEEKPLANNING_TIMEZONE', 'Europe/Brussels'),
    ],

    'tiles' => [
        'attendances' => [
            'emails' => [
                'sebastian@spatie.be',
                'freek@spatie.be',
                'alex@spatie.be',
                'ruben@spatie.be',
                'rias@spatie.be',
                'jef@spatie.be',
                'wouter@spatie.be',
                'niels@spatie.be',
                'tim@spatie.be'
            ],
            'keywords' => [
                'home' => ['thuis', 'verlof', 'ziek'],
                'office' => ['kantoor'],
            ],
            'missingKeywordMeansAtOffice' => false,
        ],

        'calendar' => [
            'ids' => [
                env('GOOGLE_CALENDAR_ID'),
            ],
            'refresh_interval_in_seconds' => 3,
        ],

        'twitter' => [
            'configurations' => [
                'default' => [
                    'consumer_key' => env('TWITTER_CONSUMER_KEY'),
                    'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
                    'access_token' => env('TWITTER_ACCESS_TOKEN'),
                    'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
                    'listen_for' => [
                        'spatie.be',
                        '@spatie_be',
                        'github.com/spatie',
                    ],
                ],
            ],
        ],

        'velo' => [
            'stations' => explode(',', env('VELO_STATIONS', '')),
        ],

        'belgian_trains' => [
            'connections' => [
                [
                    'departure' => 'Antwerpen-Centraal',
                    'destination' => 'Gent-Dampoort',
                    'label' => 'Gent',
                ],
                [
                    'departure' => 'Antwerpen-Centraal',
                    'destination' => 'Noorderkempen (Brecht)',
                    'label' => 'Brecht',
                ],
            ]
        ],

        'officient' => [
            'wfh_keywords' => ['thuiswerk', 'thuis', 'home', 'wfh', 'work from home'],
        ],

        'time_weather' => [
            'open_weather_map_key' => env('OPEN_WEATHER_MAP_KEY'),
            'open_weather_map_city' => 'Antwerp',
            'buienradar_latitude' => env('BUIENRADAR_LATITUDE'),
            'buienradar_longitude' => env('BUIENRADAR_LONGITUDE'),
        ],
    ],
];
