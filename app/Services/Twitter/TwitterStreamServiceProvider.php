<?php

namespace App\Services\Twitter;

use Illuminate\Support\ServiceProvider;
use Phirehose;

class TwitterStreamServiceProvider extends ServiceProvider
{
    public function register()
    {


        $this->app->bind(TwitterStream::class, function () {

            $config = config('twitter');

            $twitterStream = new TwitterStream(
                $config['access_token'],
                $config['access_token_secret'],
                Phirehose::METHOD_FILTER
            );

            $twitterStream->consumerKey = $config['consumer_key'];
            $twitterStream->consumerSecret = $config['consumer_secret'];

            return $twitterStream;
        });
    }
}
