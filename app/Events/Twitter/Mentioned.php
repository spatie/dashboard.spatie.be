<?php

namespace App\Events\Twitter;

use App\Events\DashboardEvent;

class Mentioned extends DashboardEvent
{
    public $tweetProperties;

    public function __construct(array $tweetProperties)
    {
        $this->tweetProperties = $tweetProperties;
    }
}
