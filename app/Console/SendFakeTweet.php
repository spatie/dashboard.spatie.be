<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Events\Twitter\Mentioned;
use Illuminate\Foundation\Inspiring;

class SendFakeTweet extends Command
{
    protected $signature = 'dashboard:send-fake-tweet {text?} {--Q|quote : Attach a quote to the tweet}';

    protected $description = 'Send a fake tweet.';

    public function handle()
    {
        $text = $this->argument('text') ?? Inspiring::quote();

        $quote = $this->option('quote')
            ? Inspiring::quote()
            : '';

        event(new Mentioned($this->getFakeTweetProperties($text, $quote)));
    }

    protected function getFakeTweetProperties(string $text, string $quote): array
    {
        $stubName = empty($quote)
            ? 'regularTweet'
            : 'tweetWithQuote';

        $tweetContent = file_get_contents(resource_path("stubs/tweet/{$stubName}.json"));

        collect([
            'text' => $text,
            'quote' => $quote,
            'currentTime' => Carbon::now()->subHour()->format('D M d H:i:s +0000 Y'),
            'textLength' => strlen($text)
        ])->reduce(function ($tweetContent, $replace, $search) {
            return str_replace($search, $replace, $tweetContent);
        }, $tweetContent);

        return json_decode($tweetContent);
    }
}
