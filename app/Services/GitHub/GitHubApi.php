<?php

namespace App\Services\GitHub;

use Github\Client;
use Github\ResultPager;
use Illuminate\Support\Collection;

class GitHubApi
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchPublicRepositories(string $userName): Collection
    {
        $repos = $this->fetchAllResults('organization', 'repositories', [$userName]);

        return collect($repos)->filter(function ($repo) {
            return $repo['private'] === false;
        });
    }

    public function fetchContributors(string $userName, string $repoName): Collection
    {
        return collect($this->fetchAllResults('repo', 'statistics', [$userName, $repoName]));
    }

    public function fetchPullRequests(string $userName, string $repoName): Collection
    {
        return collect($this->fetchAllResults('pull_request', 'all', [$userName, $repoName]));
    }

    public function fetchFileContent($userName, $repoName, $fileName, $branchName): array
    {
        return $this->client->repo()->contents()->show(
            $userName,
            $repoName,
            $fileName,
            $branchName
        );
    }

    /**
     * @param string $interfaceName
     * @param string $method
     * @param array $parameters
     *
     * @return array|string
     */
    protected function fetchAllResults(string $interfaceName, string $method, array $parameters)
    {
        return (new ResultPager($this->client))->fetchAll(
            $this->client->api($interfaceName),
            $method,
            $parameters
        );
    }
}
