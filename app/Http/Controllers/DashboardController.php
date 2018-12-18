<?php

namespace App\Http\Controllers;

use App\Services\StaffTool\RoomMember;
use App\Services\StaffTool\LeaveRequest;
use App\Services\Jira\JiraUser;

class DashboardController
{
    public function __invoke()
    {
        $members = RoomMember::all();
        $jiraUser = new JiraUser();
        $users = $jiraUser->fetchMany(array_column($members, 'trac'));

        return view('dashboard')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'clientConnectionPath' => config('websockets.client_connection_path'),
            'members' => $members,
            'jira' => $users,
            'leaveRequests' => LeaveRequest::all(),
        ]);
    }
}
