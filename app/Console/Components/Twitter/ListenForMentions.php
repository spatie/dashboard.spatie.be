<?php

namespace App\Console\Components\Twitter;

use App\Events\Twitter\Mentioned;
use Illuminate\Console\Command;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;

class ListenForMentions extends Command
{
    protected $signature = 'dashboard:listen-twitter-mentions';

    protected $description = 'Listen for mentions on Twitter';

    public function handle()
    {
        $this->info('Listening for mentions...');

        app(TwitterStreamingApi::class)
            ->publicStream()
            ->whenHears([
                'spatie.be',
                '@spatie_be',
                'github.com/spatie',
            ], function (array $tweetProperties) {
                event(new Mentioned($tweetProperties));
            })
            ->startListening();
    }
}
