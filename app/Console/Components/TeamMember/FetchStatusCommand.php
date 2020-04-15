<?php

namespace App\Console\Components\TeamMember;

use App\Support\Slack\Slack;
use App\Support\Slack\Member;
use App\Support\TeamMemberStore;
use Illuminate\Console\Command;
use App\Events\TeamMember\UpdateStatus;

class FetchStatusCommand extends Command
{
    protected $signature = 'dashboard:fetch-team-member-status';

    protected $description = 'Determine team member statuses';

    protected $slackMembers = [
        'seb',
        'adriaan',
        'freek',
        'willem',
        'alex',
        'ruben',
        'rias',
        'brent',
        'jef',
        'wouter',
    ];

    public function handle(Slack $slack)
    {
        $this->info('Fetching team member statuses from Slack...');

        $slack
            ->getMembers($this->slackMembers)
            ->each(function (Member $member) {
                TeamMemberStore::find(strtolower($member->name))->setStatusEmoji($member->statusEmoji);
            });

        $this->info('All done!');
    }
}
