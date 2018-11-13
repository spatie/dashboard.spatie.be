<?php

namespace App\Events\Velo;

use App\Events\DashboardEvent;

class StationsFetched extends DashboardEvent
{
    /** @var array */
    public $stations;

    public function __construct(array $stations)
    {
        $this->stations = $stations;
    }
}
