<?php

namespace App\Support\GitHub;

use Github\Client;
use Illuminate\Support\ServiceProvider;

class GitHubServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GitHubApi::class, function () {
            $client = new Client();

            $client->authenticate(config('services.github.token'), null, Client::AUTH_HTTP_TOKEN);

            return new GitHubApi($client);
        });
    }
}
