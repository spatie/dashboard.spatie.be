<?php

namespace App\Events\Music;

use App\Events\DashboardEvent;

class TeamMemberPlayingNothing extends DashboardEvent
{
    /** @var string */
    public $teamMemberName;

    public function __construct(string $teamMemberName)
    {
        $this->teamMemberName = $teamMemberName;
    }
}
