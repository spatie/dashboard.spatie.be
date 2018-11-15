<?php

namespace App\Console;

use Illuminate\Console\Command;

class UpdateDashboardCommand extends Command
{
    protected $signature = 'dashboard:update';

    protected $description = 'Update all components displayed on the dashboard.';

    public function handle()
    {
        $this->call('dashboard:fetch-github-totals');
        $this->call('dashboard:fetch-calendar-events');
        $this->call('dashboard:send-heartbeat');
        $this->call('dashboard:fetch-current-tracks');
        $this->call('dashboard:fetch-packagist-totals');
        $this->call('dashboard:fetch-tasks');
        $this->call('dashboard:fetch-velo-stations');
    }
}