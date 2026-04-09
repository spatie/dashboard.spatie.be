<?php

namespace App\Console\Commands;

use App\Models\Deploy;
use Illuminate\Console\Command;

class RefreshDashboardCommand extends Command
{
    protected $signature = 'dashboard:refresh';

    protected $description = 'Refresh all open dashboards';

    public function handle(): void
    {
        Deploy::create();

        $this->info('All open dashboards will refresh shortly.');
    }
}
