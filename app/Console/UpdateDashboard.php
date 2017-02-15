<?php

namespace App\Console;

use Illuminate\Console\Command;

class UpdateDashboard extends Command
{
    protected $signature = 'dashboard:update';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('dashboard:calendar');
        $this->call('dashboard:github');
        $this->call('dashboard:heartbeat');
        $this->call('dashboard:lastfm');
        $this->call('dashboard:packagist');
        $this->call('dashboard:rain');
    }
}
