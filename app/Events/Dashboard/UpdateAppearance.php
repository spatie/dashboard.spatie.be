<?php

namespace App\Events\Dashboard;

use App\Events\DashboardEvent;

class UpdateAppearance extends DashboardEvent
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
