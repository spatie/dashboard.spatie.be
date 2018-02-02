<?php

namespace App\Console\Components\Twitter;

use Illuminate\Console\Command;
use App\Events\Twitter\Mentioned;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;

class ListenForQuotes extends Command
{
    protected $signature = 'dashboard:listen-twitter-quotes';

    protected $description = 'Listen for quotes on Twitter';

    public function handle()
    {
        $this->info('Listening for quotes...');

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
