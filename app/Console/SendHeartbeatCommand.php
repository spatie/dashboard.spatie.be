<?php

namespace App\Console;

use Illuminate\Console\Command;
use App\Events\Heartbeat;

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
