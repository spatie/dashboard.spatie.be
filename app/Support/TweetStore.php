<?php

namespace App\Support;

use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\File;

class TweetStore
{
    private Valuestore $valuestore;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $path = storage_path("app/dashboard");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/tweets.json");
    }

    public function addTweet(array $tweetProperties)
    {
        $tweets = $this->valuestore->get('tweets', []);

        array_unshift($tweets, $tweetProperties);

        $tweets = array_slice($tweets, 0, 50);

        $this->valuestore->put('tweets', $tweets);
    }

    public function tweets(): array
    {
        return collect($this->valuestore->get('tweets', []))
            ->map(fn (array $tweetAttributes) => new Tweet($tweetAttributes))
            ->reject(fn (Tweet $tweet) => $tweet->bySpatie())
            ->toArray();
    }
}
