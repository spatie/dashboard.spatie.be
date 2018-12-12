<?php

namespace App\Http\Controllers;

use App\Services\StaffTool\RoomMember;

class StaffController
{
    /** @var int */
    private $adRoomId = 28;

    public function __invoke()
    {
        $roomMember = new RoomMember($this->adRoomId);

        $roomMember->resync();
        $roomMember->archive('jadephung');

        return view('staff')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'clientConnectionPath' => config('websockets.client_connection_path'),
            'members' => $roomMember->getMembers(),
            'archived' => $roomMember->getArchived(),
        ]);
    }
}
