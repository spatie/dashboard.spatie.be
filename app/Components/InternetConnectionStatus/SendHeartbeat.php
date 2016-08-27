<?php

namespace App\Components\InternetConnectionStatus;

use App\Events\InternetConnectionStatus\Heartbeat;
use Illuminate\Console\Command;

class SendHeartbeat extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a heartbeat to help the client verify if it is connected to the internet.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        event(new Heartbeat());
    }
}
