<?php

namespace App\Events\Dashboard;

use App\Events\DashboardEvent;

class UpdateAppearance extends DashboardEvent
{
    /** @var string */
    public $mode;

    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }
}
