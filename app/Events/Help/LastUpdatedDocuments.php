<?php

namespace App\Events\Help;

use App\Events\DashboardEvent;

class LastUpdatedDocuments extends DashboardEvent
{
    public $documents;

    public function __construct($documents)
    {
        $this->documents = $documents;
    }
}
