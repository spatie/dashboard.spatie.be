<?php

namespace App\Console\Components\Twitter;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Events\Twitter\Mentioned;
use Illuminate\Foundation\Inspiring;

class SendFakeTweet extends Command
{
    protected $signature = 'dashboard:send-fake-tweet {text?} {--Q|quote : Attach a quote to the tweet}';

    protected $description = 'Send a fake tweet';

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

        $tweetStub = file_get_contents(resource_path("stubs/tweets/{$stubName}.json"));

        $tweetContent = $this->fillPlaceHolders($tweetStub, [
            '%text%' => $text,
            '%quote%' => $quote,
            '%currentTime%' => Carbon::now()->subHour()->format('D M d H:i:s +0000 Y'),
            '%textLength%' => strlen($text),
        ]);

        return json_decode($tweetContent, true);
    }

    protected function fillPlaceHolders(string $text, array $replacements): string
    {
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $text
        );
    }
}
