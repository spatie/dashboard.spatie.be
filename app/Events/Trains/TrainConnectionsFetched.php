<?php

namespace App\Events\Trains;

use App\Events\DashboardEvent;

class TrainConnectionsFetched extends DashboardEvent
{
    /** @var array */
    public $trainConnections;

    public function __construct(array $trainConnections)
    {
        $this->trainConnections = $trainConnections;
    }
}
