<?php

namespace App\Events\Tasks;

use App\Events\DashboardEvent;

class TasksFetched extends DashboardEvent
{
    /** @var array */
    public $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }
}
