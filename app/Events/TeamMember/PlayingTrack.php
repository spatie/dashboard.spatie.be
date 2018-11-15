<?php

namespace App\Events\TeamMember;

use App\Events\DashboardEvent;

class PlayingTrack extends DashboardEvent
{
    /** @var string */
    public $teamMemberName;

    /** @var array */
    public $trackInfo;

    public function __construct(string $teamMemberName, array $trackInfo)
    {
        $this->teamMemberName = $teamMemberName;

        $this->trackInfo = $trackInfo;
    }
}
