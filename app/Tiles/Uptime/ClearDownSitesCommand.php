<?php

namespace App\Tiles\Uptime;

use App\Tiles\Uptime\UptimeStore;
use Illuminate\Console\Command;

class ClearDownSitesCommand extends Command
{
    protected $signature = 'dashboard:clear-down-sites';

    protected $description = 'Clear all sites that are down';

    public function handle()
    {
        $this->info('Clear down sites...');

        UptimeStore::make()->clearDownSites();

        $this->info('All done!');
    }
}
