<?php

use Monolog\Level;
use Spatie\FlareClient\Sampling\RateSampler;
use Spatie\FlareClient\Senders\DaemonSender;
use Spatie\LaravelFlare\FlareConfig;
use Spatie\LaravelFlare\Senders\LaravelHttpSender;

return [
    /*
    |
    |--------------------------------------------------------------------------
    | Flare API key
    |--------------------------------------------------------------------------
    |
    | Specify Flare's API key below to enable error reporting to the service.
    |
    | More info: https://flareapp.io/docs/flare/general/getting-started
    |
    */

    'key' => env('FLARE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Collects
    |--------------------------------------------------------------------------
    |
    | Flare will collect a lot of information about your application. You can
    | disable some of the collectors here, configure them or add your own.
    |
    */

    'collects' => FlareConfig::defaultCollects(
        ignore: [],
        extra: []
    ),

    /*
    |--------------------------------------------------------------------------
    | Censor data
    |--------------------------------------------------------------------------
    |
    | It is possible to censor sensitive data from the reports and sent to
    | Flare. Below you can specify which fields and header should be
    | censored. It is also possible to hide the client's IP address.
    |
    */

    'censor' => [
        'body_fields' => [
            'password',
            'password_confirmation',
        ],
        'headers' => [
            'API-KEY',
            'Authorization',
            'Cookie',
            'Set-Cookie',
            'X-CSRF-TOKEN',
            'X-XSRF-TOKEN',
        ],
        'client_ips' => false,
        'cookies' => false,
        'session' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sender
    |--------------------------------------------------------------------------
    |
    | The sender is responsible for sending the error reports and traces to
    | Flare. By default, Laravel Flare sends them over HTTP. To use the local
    | Flare daemon, switch the sender class to
    | `Spatie\FlareClient\Senders\DaemonSender::class` and set `daemon_url`.
    | The daemon sender defaults to localhost on port 8787 and uses its own
    | default timeouts and fallback sender config unless you override them.
    |
    */

    'sender' => env('FLARE_DAEMON_URL')
        ? [
            'class' => DaemonSender::class,
            'config' => [
                'daemon_url' => env('FLARE_DAEMON_URL'),
            ],
        ]
        : [
            'class' => LaravelHttpSender::class,
            'config' => [
                'timeout' => 10,
            ],
        ],

    /*
    |--------------------------------------------------------------------------
    | Report
    |--------------------------------------------------------------------------
    |
    | Flare reports errors and exceptions happening within your application.
    |
    */

    'report' => env('FLARE_REPORT', true),

    /*
    |--------------------------------------------------------------------------
    | Report error levels
    |--------------------------------------------------------------------------
    | When reporting errors, you can specify which error levels should be
    | reported. By default, all error levels are reported by setting
    | this value to `null`.
    */

    'report_error_levels' => null,

    /*
    |--------------------------------------------------------------------------
    | Override grouping
    |--------------------------------------------------------------------------
    |
    | Flare will try to group errors and exceptions as best as possible, that
    | being said, sometimes you might want to override the grouping. You can
    | do this by adding exception classes to this array which should always
    | be grouped by exception class, exception message or exception class
    | and message.
    |
    */

    'overridden_groupings' => [
        // Illuminate\Http\Client\ConnectionException::class => Spatie\FlareClient\Enums\OverriddenGrouping::ExceptionMessageAndClass,
    ],

    /*
    |--------------------------------------------------------------------------
    | Share button
    |--------------------------------------------------------------------------
    |
    | Flare automatically adds a Share button to the laravel error page. This
    | button allows you to easily share errors with colleagues or friends. It
    | is enabled by default, but you can disable it here.
    |
    */

    'enable_share_button' => true,

    /*
    |--------------------------------------------------------------------------
    | Trace
    |--------------------------------------------------------------------------
    |
    | Tracing allows you to see the flow of your application. It shows you
    | which parts of your application are slow and which parts are fast.
    |
    */

    'trace' => env('FLARE_TRACE', true),

    /*
    |--------------------------------------------------------------------------
    | Sampler
    |--------------------------------------------------------------------------
    |
    | The sampler is used to determine which traces should be recorded and
    | which traces should be dropped. It is possible to set the rate
    | at which traces should be recorded. The default rate is 0.1
    | which means that 10% of the traces will be recorded.
    |
    */

    'sampler' => [
        'class' => RateSampler::class,
        'config' => [
            'rate' => env('FLARE_SAMPLER_RATE', 0.1),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Log
    |--------------------------------------------------------------------------
    |
    | Logging show you an overview of log entries within your application.
    | Logs are only sent when a channel using the `flare` driver is part of
    | your logging stack in `config/logging.php`. You can disable the
    | collection of logs by setting `FLARE_LOG=false`.
    |
    */

    'log' => env('FLARE_LOG', true),

    /*
    |--------------------------------------------------------------------------
    | Minimal log level
    |--------------------------------------------------------------------------
    |
    | You can specify the minimal (Monolog) log level that should be sent to Flare.
    | Log levels lower than the specified level will be ignored.
    | If null all log levels will be sent to Flare.
    |
    */

    'minimal_log_level' => Level::Info,
];
