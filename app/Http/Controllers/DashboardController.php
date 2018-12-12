<?php

namespace App\Http\Controllers;

use App\Services\TweetHistory\TweetHistory;
use App\Services\StaffTool\RoomMember;

class DashboardController
{
    public function __invoke()
    {
        return view('dashboard')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'clientConnectionPath' => config('websockets.client_connection_path'),
            'initialTweets' => TweetHistory::all(),
            'members' => RoomMember::all(),
        ]);
    }
}
