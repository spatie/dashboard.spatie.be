<?php

namespace App\Components\GitHub;

use App\Events\GitHub\StatisticsFetched;
use App\Events\GitHub\TotalsFetched;
use App\Services\GitHub\GitHubApi;
use GitHub;
use Github\Client;
use Github\ResultPager;
use Illuminate\Console\Command;

class FetchGitHubStatistics extends Command
{
    protected $signature = 'dashboard:github-statistics';

    protected $description = 'Fetch GitHub forks, issues, PRs.';

    public function handle(GitHubApi $api)
    {
        $userName = config('services.github.username');

        $totals = $api
            ->fetchPublicRepositories($userName)
            ->pipe(function ($repos) use ($api, $userName) {
                return [
                    'stars' => $repos->sum('stargazers_count'),
                    'forks' => $repos->sum('forks_count'),
                    'issues' => $repos->sum('open_issues'),
                    'pullRequests' => $repos->sum(function ($repo) use ($api, $userName) {
                        return count($api->fetchPullRequests($userName, $repo['name']));
                    }),
                    'contributors' => $repos->sum(function ($repo) use ($api, $userName) {
                        return count($api->fetchContributors($userName, $repo['name']));
                    }),

                ];
            });

        dd($totals);

        event(new StatisticsFetched($totals));
    }
}
