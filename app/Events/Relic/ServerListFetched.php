<?php

namespace App\Events\Relic;

use App\Events\DashboardEvent;

class ServerListFetched extends DashboardEvent
{
    /** @var array */
    public $servers;

    public function __construct(array $servers)
    {
        $this->servers = $servers;
    }
}
