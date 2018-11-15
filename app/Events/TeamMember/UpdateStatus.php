<?php

namespace App\Events\TeamMember;

use App\Events\DashboardEvent;

class UpdateStatus extends DashboardEvent
{
    /** @var string */
    public $teamMemberName;

    /** @var string */
    public $statusEmoji;

    public function __construct(string $teamMemberName, string $statusEmoji, bool $isBirthDay)
    {
        $this->teamMemberName = $teamMemberName;

        $this->statusEmoji = $statusEmoji;

        $this->isBirthDay = $isBirthDay;
    }
}
