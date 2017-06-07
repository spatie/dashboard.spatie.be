<?php

namespace App\Components\InternetConnectionStatus;

use Illuminate\Console\Command;
use App\Events\InternetConnectionStatus\Heartbeat;

class SendHeartbeat extends Command
{
    protected $signature = 'dashboard:send-heart';

    protected $description = 'Send a heartbeat to help the client verify that it is connected to the internet.';

    public function handle()
    {
        event(new Heartbeat());
    }
}
