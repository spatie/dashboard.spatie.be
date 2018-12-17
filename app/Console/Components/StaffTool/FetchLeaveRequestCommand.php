<?php

namespace App\Console\Components\StaffTool;

use Illuminate\Console\Command;
use App\Services\StaffTool\RoomMember;
use App\Services\StaffTool\LeaveRequest;
use Illuminate\Support\Collection;

class FetchLeaveRequestCommand extends Command
{
    protected $signature = 'dashboard:fetch-staff-leaves';

    protected $description = 'Fetch user leave requests';

    public function handle(LeaveRequest $staffLeave)
    {
        $this->info('Fetching user leave requests');

        $members = RoomMember::all();
        $nocache = true;
        $rs = $staffLeave->fetchMany(
            array_combine(array_column($members, 'trac'), array_column($members, 'name')), 
            $nocache
        );

        $this->info('All done!');
    }
}
