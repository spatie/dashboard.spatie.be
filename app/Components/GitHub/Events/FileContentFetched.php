<?php

namespace App\Components\GitHub\Events;

use App\Components\DashboardEvent;

class FileContentFetched extends DashboardEvent
{
    /** @var array */
    public $fileContent;

    public function __construct(array $fileContent)
    {
        $this->fileContent = $fileContent;
    }
}
