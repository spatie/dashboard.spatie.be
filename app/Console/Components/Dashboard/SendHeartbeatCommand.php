<?php

namespace App\Console\Components\Dashboard;

use App\Events\Dashboard\Heartbeat;
use Illuminate\Console\Command;

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
