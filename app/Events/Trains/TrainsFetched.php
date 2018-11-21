<?php

namespace App\Events\Trains;

use App\Events\DashboardEvent;

class TrainsFetched extends DashboardEvent
{
    /** @var array */
    public $trains;

    public function __construct(array $trains)
    {
        $this->trains = $trains;
    }
}
