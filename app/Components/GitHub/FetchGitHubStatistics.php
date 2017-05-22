<?php

namespace App\Components\GitHub;

use Illuminate\Console\Command;
use App\Services\GitHub\GitHubApi;
use Illuminate\Support\Collection;
use App\Events\GitHub\StatisticsFetched;

class FetchGitHubStatistics extends Command
{
    protected $signature = 'dashboard:github-statistics';

    protected $description = 'Fetch GitHub statistics.';

    public function handle(GitHubApi $api)
    {
        $userName = config('services.github.username');

        $totals = $api
            ->fetchPublicRepositories($userName)
            ->pipe(function (Collection $repos) use ($api, $userName) {
                return [
                    'stars' => $repos->sum('stargazers_count'),
                    'issues' => $repos->sum('open_issues'),
                    'pullRequests' => $repos->sum(function ($repo) use ($api, $userName) {
                        return count($api->fetchPullRequests($userName, $repo['name']));
                    }),
                    'contributors' => $repos->sum(function ($repo) use ($api, $userName) {
                        return count($api->fetchContributors($userName, $repo['name']));
                    }),
                    'numberOfRepos' => $repos->count(),
                ];
            });

        event(new StatisticsFetched($totals));
    }
}
