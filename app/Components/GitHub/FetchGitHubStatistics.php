<?php

namespace App\Components\GitHub;

use App\Events\GitHub\StatisticsFetched;
use App\Events\GitHub\TotalsFetched;
use GitHub;
use Github\Client;
use Github\ResultPager;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FetchGitHubStatistics extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:github-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch GitHub forks, issues, PRs.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userName = env('GITHUB_USERNAME');

        $repos = $this->getPublicRepositories($userName);

        $stars = $repos->sum('stargazers_count');
        $issues = $repos->sum('open_issues');
        $forks = $repos->sum('forks_count');

        $contributors = $repos->sum(function ($repo) use ($userName) {
            return count($this->getContributors($userName, $repo['name']));
        });

        $pullRequests = $repos->sum(function ($repo) use ($userName) {
            return count($this->getPullRequests($userName, $repo['name']));
        });

        $totals = compact('stars', 'issues', 'forks', 'pullRequests', 'contributors');

        dd($totals);

        event(new StatisticsFetched($totals));
    }

    protected function getPublicRepositories(string $userName): Collection
    {
        $client = $this->getAuthenticatedClient();

        $paginator = new ResultPager($client);

        $repos = $paginator->fetchAll(
            $client->api('organization'),
            'repositories',
            [$userName]
        );

        return collect($repos)->filter(function($repo) {
            return $repo['private'] === false;
        });
    }

    protected function getPullRequests($userName, $repoName)
    {
        $client = $this->getAuthenticatedClient();

        $paginator = new ResultPager($client);

        $pullRequest = $paginator->fetchAll(
            $client->api('pull_request'),
            'all',
            [$userName, $repoName]
        );

        return collect($pullRequest);
    }

    protected function getContributors($userName, $repoName)
    {
        $client = $this->getAuthenticatedClient();

        $paginator = new ResultPager($client);

        $contributors = $paginator->fetchAll(
            $client->api('repo'),
            'statistics',
            [$userName, $repoName]
        );

        return collect($contributors);
    }

    protected function getAuthenticatedClient()
    {
        $client = new Client();

        $client->authenticate(env('GITHUB_TOKEN'), null, Github\Client::AUTH_HTTP_TOKEN);

        return $client;
    }


}