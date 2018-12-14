<?php

namespace App\Http\Controllers;

use App\Services\StaffTool\RoomMember;

class StaffController
{
    public function __invoke()
    {
        $roomMember = new RoomMember();

        $roomMember->resync();
        // $roomMember->archive('phonglh');
        // $roomMember->archive('jadephung');
        // $roomMember->archive('binh.nguyen');
        // $roomMember->archive('minhthucdao');
        // $roomMember->archive('thanhcong.vu');
        // $roomMember->archive('vlk');
        // $roomMember->archive('sam');
        // $roomMember->archive('deivas');

        return view('staff')->with([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'clientConnectionPath' => config('websockets.client_connection_path'),
            'members' => $roomMember->getMembers(),
            'archived' => $roomMember->getArchived(),
        ]);
    }
}
