<?php

namespace App\Tiles\TeamMember\Commands;

use App\Services\Slack\Member;
use App\Services\Slack\Slack;
use App\Tiles\TeamMember\TeamMemberStore;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchSlackStatusCommand extends Command
{
    protected $signature = 'dashboard:fetch-team-member-status';

    protected $description = 'Determine team member statuses';

    public function handle(Slack $slack): void
    {
        $this->info('Fetching team member statuses from Slack...');

        $members = collect(cache()->remember('members', now()->addDay(), function () {
            return Http::withToken(config('services.spatie.token'))
                ->get('https://spatie.be/api/members')
                ->json();
        }))->pluck('name')
            ->map(fn ($name) => strtolower($name))
            ->toArray();

        try {
            $slack
                ->getMembers($members)
                ->each(function (Member $member) {
                    TeamMemberStore::find(strtolower($member->name))->setStatusEmoji($member->statusEmoji);
                });
        } catch (ClientException $e) {
            if ($e->getCode() === 429) {
                return;
            }

            throw $e;
        }

        $this->info('All done!');
    }
}
