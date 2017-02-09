<?php

namespace App\Components\Twitter;

use App\Events\Twitter\Mentioned;
use Illuminate\Console\Command;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;

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

        app(TwitterStreamingApi::class)
            ->publicStream()
            ->whenHears(['spatie.be', '@spatie_be', 'github.com/spatie'], function (array $tweetProperties) {
                event(new Mentioned($tweetProperties));
            })
            ->startListening();

        app(TwitterStreamingApi::class)
            ->userStream()
            ->onEvent(function(array $event) {
                if (isset($event['event']) && $event['event'] === 'quoted_tweet') {
                    dump($event);
                    event(new Mentioned($event['target_object']));
                }
            })
            ->startListening();
    }
}
