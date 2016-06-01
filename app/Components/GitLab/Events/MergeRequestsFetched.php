<?php

namespace App\Components\GitLab\Events;

use App\Components\DashboardEvent;

class MergeRequestsFetched extends DashboardEvent
{
    /** @var array */
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
