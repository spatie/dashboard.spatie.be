<?php

namespace App\Console\Components\TeamMember;

use Illuminate\Console\Command;
use Parsedown;

class FetchStatusCommand extends Command
{
    protected $signature = 'dashboard:fetch-team-member-status';

    protected $description = 'Fetch team members statusses from Slack';

    public function handle()
    {

    }
}
