<?php

namespace App\Console\Components\Dashboard;

use Illuminate\Console\Command;
use App\Events\Dashboard\Heartbeat;

class SendHeartbeatCommand extends Command
{
    protected $signature = 'dashboard:send-heartbeat';

    protected $description = 'Send a heartbeat to the internet connection tile';

    public function handle()
    {
        $this->info('Sending heartbeat...');

        event(new Heartbeat());

        $this->info('All done!');
    }
}
