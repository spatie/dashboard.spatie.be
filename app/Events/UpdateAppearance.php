<?php

namespace App\Events;

use App\Events\DashboardEvent;

class UpdateAppearance
{
    /** @var string */
    public $mode;

    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }
}
