<?php

namespace App\Console\Components\Twitter;

use Illuminate\Console\Command;
use App\Events\Twitter\Mentioned;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;

class ListenForMentions extends Command
{
    protected $signature = 'dashboard:twitter';

    protected $description = 'Listen for mentions on Twitter';

    public function handle()
    {
        $this->info('Listening for tweets...');

        $this->listenForMentions([
            'spatie.be',
            '@spatie_be',
            'github.com/spatie'
        ]);

        $this->listenForQuoted();
    }

    protected function listenForMentions(array $listenFor)
    {
        app(TwitterStreamingApi::class)
            ->publicStream()
            ->whenHears($listenFor, function (array $tweetProperties) {
                event(new Mentioned($tweetProperties));
            })
            ->startListening();
    }

    protected function listenForQuoted()
    {
        app(TwitterStreamingApi::class)
            ->userStream()
            ->onEvent(function (array $event) {
                if (isset($event['event']) && $event['event'] === 'quoted_tweet') {
                    event(new Mentioned($event['target_object']));
                }
            })
            ->startListening();
    }
}
