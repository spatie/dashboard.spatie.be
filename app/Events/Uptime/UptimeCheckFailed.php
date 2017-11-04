<?php

namespace App\Events\Uptime;

use App\Events\DashboardEvent;

class UptimeCheckFailed extends DashboardEvent
{
    /** @var int */
    public $id;

    /** @var string */
    public $url;

    public function __construct(int $siteId, string $siteUrl)
    {
        $this->id = $siteId;

        $this->url = $siteUrl;
    }
}
