<?php

namespace App\Components\Twitter;

use App\Events\Twitter\Mentioned;
use Illuminate\Console\Command;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;

class ListenForMentions extends Command
{
    protected $signature = 'dashboard:twitter';

    protected $description = 'Listen for mentions on twitter.';

    public function handle()
    {
        $this->info('Listening for tweets...');

        app(TwitterStreamingApi::class)
            ->publicStream()
            ->whenHears(['spatie.be', '@spatie_be', 'github.com/spatie'], function (array $tweetProperties) {
                event(new Mentioned($tweetProperties));
            })
            ->startListening();

        app(TwitterStreamingApi::class)
            ->userStream()
            ->onEvent(function (array $event) {
                if (isset($event['event']) && $event['event'] === 'quoted_tweet') {
                    dump($event);
                    event(new Mentioned($event['target_object']));
                }
            })
            ->startListening();
    }
}
