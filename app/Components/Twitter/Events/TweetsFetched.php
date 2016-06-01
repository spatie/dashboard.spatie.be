<?php

namespace App\Components\Twitter\Events;

use App\Components\DashboardEvent;

class TweetsFetched extends DashboardEvent
{
    /** @var array */
    public $tweets;

    public function __construct(array $tweets)
    {
        $this->tweets = $tweets;
    }
}