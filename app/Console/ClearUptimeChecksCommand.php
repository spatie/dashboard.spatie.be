<?php

namespace App\Console;

use Illuminate\Console\Command;
use App\Events\Uptime\ClearingUptimeChecks;

class ClearUptimeChecksCommand extends Command
{
    protected $signature = 'dashboard:clear-uptime-checks';

    protected $description = 'Clears all uptime checks from the dashboard';

    public function handle()
    {
        $this->info('Clearing uptime checks...');

        event(new ClearingUptimeChecks());

        $this->info('All done!');
    }
}
