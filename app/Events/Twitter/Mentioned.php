<?php

namespace App\Events\Twitter;

use App\Events\DashboardEvent;

class Mentioned extends DashboardEvent
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
