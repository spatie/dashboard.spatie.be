<?php

namespace App\Console\Components\GitHub;

use App\Events\GitHub\TotalsDetailFetched;
use Illuminate\Console\Command;
use App\Services\GitHub\GitHubApi;
use Illuminate\Support\Collection;

class FetchTotalDetails extends Command
{
    protected $signature = 'dashboard:fetch-github-total-details';

    protected $description = 'Fetch GitHub repo';

    public function handle(GitHubApi $gitHub)
    {
        $userName = config('services.github.username');

        $totals = $gitHub
            ->fetchRepositories($userName)
            ->keyBy('name')
            ->reject->fork
            ->map(function ($repo) use ($gitHub, $userName) {
                $pullRequests = $gitHub->fetchPullRequests($userName, $repo['name']);

                return [
                    'name' => $repo['name'],
                    'pullRequests' => [
                        'count' => $pullRequests->count(),
                        'labels' => $pullRequests
                            ->flatMap(function ($pullRequest) use ($gitHub, $userName, $repo) {
                                return $gitHub->fetchLabels($userName, $repo['name'], $pullRequest['number']);
                            })
                            ->groupBy('id')
                            ->map(function (Collection $labels) {
                                return [
                                    'count' => $labels->count(),
                                    'name' => $labels->first()['name'],
                                    'color' => $labels->first()['color'],
                                ];
                            }),
                    ],
                ];
            });

        event(new TotalsDetailFetched($totals));
    }
}
