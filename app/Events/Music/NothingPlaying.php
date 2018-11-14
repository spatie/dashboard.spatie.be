<?php

namespace App\Events\Music;

use App\Events\DashboardEvent;

class NothingPlaying extends DashboardEvent
{
    /** @var string */
    public $user;

    public function __construct(string $user)
    {
        $this->user = $user;
    }
}
