<?php

namespace App\Events\Music;

use App\Events\DashboardEvent;

class TeamMemberPlayingTrack extends DashboardEvent
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
