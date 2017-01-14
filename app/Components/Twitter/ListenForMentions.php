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
            ->whenHears('@spatie_be', function ($tweet) {
                $twitterUsername = $tweet['user']['screen_name'];
                $tweetText =  $tweet['text'];

                dump("Mentioned by {$twitterUsername}, {$tweetText}");

                event(new Mentioned($twitterUsername, $tweetText));
            })
            ->startListening();
    }
}
