<?php

namespace App\Console\Commands;

use App\Models\Deploy;
use Illuminate\Console\Command;

class MarkDeployCommand extends Command
{
    protected $signature = 'deploy:mark';

    protected $description = 'Mark a new deploy';

    public function handle(): void
    {
        $deploy = Deploy::create();

        Deploy::where('created_at', '<', now()->subDays(30))->delete();

        $this->info("Deploy marked at {$deploy->created_at}");
    }
}
