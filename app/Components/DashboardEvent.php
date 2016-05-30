<?php

namespace App\Components;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class DashboardEvent implements ShouldBroadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['private-dashboard'];
    }
}
