<?php

namespace App\Console\Components\InternetConnection;

use App\Events\InternetConnection\Heartbeat;
use Illuminate\Console\Command;

class SendHeartbeat extends Command
{
    protected $signature = 'dashboard:send-heartbeat';

    protected $description = 'Send a heartbeat to the internet connection tile';

    public function handle()
    {
        event(new Heartbeat());
    }
}
