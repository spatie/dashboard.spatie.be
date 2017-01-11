<?php

namespace App\Events\RainForecast;

use App\Events\DashboardEvent;

class MentionedOnTwitter extends DashboardEvent
{
    /** @var string */
    public $tweet;

    /** @var string */
    public $author;

    public function __construct(string $tweet, string $author)
    {
        $this->tweet = $tweet;

        $this->author = $author;
    }
}
