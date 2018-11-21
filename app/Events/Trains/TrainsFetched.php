<?php

namespace App\Events\Trains;

use App\Events\DashboardEvent;

class TrainsFetched extends DashboardEvent
{
    /** @var array */
    public $liveboard;

    public function __construct(array $liveboard)
    {
        $this->liveboard = $liveboard;
    }

}