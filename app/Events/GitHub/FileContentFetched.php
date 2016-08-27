<?php

namespace App\Events\GitHub;

use App\Events\DashboardEvent;

class FileContentFetched extends DashboardEvent
{
    /** @var array */
    public $fileContent;

    public function __construct(array $fileContent)
    {
        $this->fileContent = $fileContent;
    }
}
