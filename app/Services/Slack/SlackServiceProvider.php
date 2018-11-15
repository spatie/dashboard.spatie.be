<?php

namespace App\Services\Slack;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class SlackServiceProvider extends ServiceProvider
{
    public function register()
    {
        $appToken = config('services.slack.app_token');

        $this->app->singleton(Slack::class, function () use ($appToken) {
            $client = new Client([
                'base_uri' => 'https://slack.com/api',
                'headers' => [
                    'Authorization' => "Bearer {$appToken}"
                ]
            ]);

            return new Slack($client);
        });
    }
}
