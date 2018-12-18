<?php

namespace App\Console\Components\Jira;

use Illuminate\Console\Command;
use App\Services\StaffTool\RoomMember;
use App\Services\Jira\JiraUser;
use Illuminate\Support\Collection;
// use App\Events\Statistics\GitHubTotalsFetched;

class FetchJiraUsersCommand extends Command
{
    protected $signature = 'dashboard:fetch-jira';

    protected $description = 'Fetch Jira user-issues';

    public function handle(JiraUser $jiraService)
    {
        $this->info('Fetching Jira user-issues');

        if (!env('JIRA_USER') || !env('JIRA_HOST') || !env('JIRA_PASS')) {
            $this->error('Failed: JIRA_ env variables are not fully filled.');
            return;
        }

        $members = RoomMember::all();
        $nocache = true;
        $result = $jiraService->fetchMany(array_column($members, 'trac'), $nocache);

        $cntUser = count($result);
        $cntIssue = 0;
        foreach($result as $user) {
            $cntIssue += count($user['issues']);
        }

        $this->info(sprintf('All done! %d users & %d issues are scanned.', $cntUser, $cntIssue));
    }
}
