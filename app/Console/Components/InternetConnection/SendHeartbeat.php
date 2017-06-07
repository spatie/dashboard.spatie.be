<?php

namespace App\Console\Components\InternetConnection;

use Illuminate\Console\Command;
use App\Events\InternetConnection\Heartbeat;

class SendHeartbeat extends Command
{
    protected $signature = 'dashboard:send-heartbeat';

    protected $description = 'Send a heartbeat to the internet connection tile';

    public function handle()
    {
        event(new Heartbeat());
    }
}
