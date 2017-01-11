<?php

namespace App\Components\Twitter;

use App\Events\Packagist\TotalsFetched;
use App\Events\RainForecast\MentionedOnTwitter;
use App\Services\Twitter\TwitterStream;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\Packagist\Packagist;

class ListenForMentions extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for mentions on twitter.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Listening for tweets...');

        app(TwitterStream::class)
            ->whenHears('@spatie_be', function ($tweet) {
                event(new MentionedOnTwitter('tweet', 'author'));
            })
            ->startListening();
    }
}
