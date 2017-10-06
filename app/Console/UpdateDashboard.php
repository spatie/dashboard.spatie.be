<?php

namespace App\Console;

use Illuminate\Console\Command;

class UpdateDashboard extends Command
{
    protected $signature = 'dashboard:update';

    protected $description = 'Update all components displayed on the dashboard.';

    public function handle()
    {
        $this->call('dashboard:fetch-github-total-details');
        $this->call('dashboard:send-heartbeat');
    }
}
