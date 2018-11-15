<?php

namespace App\Events\TeamMember;

use App\Events\DashboardEvent;

class UpdateStatus extends DashboardEvent
{
    /** @var string */
    public $teamMemberName;

    /** @var bool */
    public $worksFromHome;

    public function __construct(string $teamMemberName, bool $worksFromHome)
    {
        $this->teamMemberName = $teamMemberName;

        $this->worksFromHome = $worksFromHome;
    }
}
