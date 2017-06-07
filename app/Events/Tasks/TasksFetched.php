<?php

namespace App\Events\Tasks;

use App\Events\DashboardEvent;

class TasksFetched extends DashboardEvent
{
    /** @var array */
    public $fileContent;

    public function __construct(array $fileContent)
    {
        $this->fileContent = $fileContent;
    }
}
