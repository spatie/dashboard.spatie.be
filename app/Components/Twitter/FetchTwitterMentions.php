<?php

namespace App\Components\Twitter;

use App\Components\Twitter\Events\TweetsFetched;
use Thujohn\Twitter\Facades\Twitter;
use Illuminate\Console\Command;

class FetchTwitterMentions extends Command
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
    protected $description = 'Fetch Twitter mentions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tweets = Twitter::getUserTimeline([
            'screen_name' => config('ttwitter.SCREENAME'),
            'count' => 10,
            'include_rts' => false,
            'include_entities' => true
        ]);

        $tweets = collect($tweets)
            ->map(function ($tweet) {

                return [
                    'created_at' => $tweet->created_at,
                    'text' => $tweet->text,
                    'image' => $this->getImageFromTweet($tweet),
                ];
            })->toArray();

        event(new TweetsFetched($tweets));
    }

    /**
     * @param $tweet
     * @return static
     */
    protected function getImageFromTweet($tweet)
    {
        return collect($tweet->entities)->filter(function ($entity, $key) {
            return $key == 'media';
        })->collapse()->map(function ($entity) {
            return $entity->media_url;
        })->first();

    }

}