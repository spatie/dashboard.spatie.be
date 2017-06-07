<?php

namespace App\Console;

use Illuminate\Console\Command;

class UpdateDashboard extends Command
{
    protected $signature = 'dashboard:update';

    public function handle()
    {
        $this->call('dashboard:calendar');
        $this->call('dashboard:github-files');
        $this->call('dashboard:github-statistics');
        $this->call('dashboard:heartbeat');
        $this->call('dashboard:lastfm');
        $this->call('dashboard:packagist');
        $this->call('dashboard:rain');
    }
}
