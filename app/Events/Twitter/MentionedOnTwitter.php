<?php

namespace App\Events\RainForecast;

use App\Events\DashboardEvent;

class MentionedOnTwitter extends DashboardEvent
{
    /** @var string */
    public $twitterUsername;

    /** @var string */
    public $tweetText;

    public function __construct(string $twitterUsername, string $tweetText)
    {
        $this->twitterUsername = $twitterUsername;

        $this->tweetText = $tweetText;
    }
}
