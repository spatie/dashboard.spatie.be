<?php

namespace App\Events\TeamMember;

use App\Events\DashboardEvent;

class PlayingNothing extends DashboardEvent
{
    /** @var string */
    public $teamMemberName;

    public function __construct(string $teamMemberName)
    {
        $this->teamMemberName = $teamMemberName;
    }
}
