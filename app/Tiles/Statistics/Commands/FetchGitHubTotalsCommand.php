<?php

namespace App\Tiles\Statistics\Commands;

use Illuminate\Console\Command;
use App\Services\GitHub\GitHubApi;
use App\Tiles\Statistics\StatisticsStore;

class FetchGitHubTotalsCommand extends Command
{
    protected $signature = 'dashboard:fetch-github-totals';

    protected $description = 'Fetch GitHub totals';

    public function handle(GitHubApi $gitHub): void
    {
        $this->info('Fetching GitHub totals');

        $userName = config('services.github.username');

        $totals = ['stars' => 0, 'issues' => 0, 'pullRequests' => 0, 'contributors' => 0];

        foreach ($gitHub->fetchPublicRepositories($userName) as $repo) {
            $totals['stars'] += $repo['stargazers_count'];
            $totals['issues'] += $repo['open_issues'];
            $totals['pullRequests'] += $gitHub->countPullRequests($userName, $repo['name']);
            $totals['contributors'] += $gitHub->countContributors($userName, $repo['name'], $repo['default_branch']);
        }

        StatisticsStore::make()->setGitHubTotals($totals);

        $this->info('All done!');
    }
}
