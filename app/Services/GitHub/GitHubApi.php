<?php

namespace App\Services\GitHub;

use Github\Client;
use RuntimeException;
use Github\ResultPager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\LazyCollection;
use Github\HttpClient\Message\ResponseMediator;
use Github\Exception\RuntimeException as GitHubException;

class GitHubApi
{
    public function __construct(protected Client $client)
    {
    }

    public function fetchPublicRepositories(string $userName): LazyCollection
    {
        return LazyCollection::make(function () use ($userName) {
            $pager = new ResultPager($this->client);
            $repositories = $pager->fetch($this->client->api('organization'), 'repositories', [$userName, 'public']);

            while (true) {
                $this->ensureCompleteResponse();

                yield from $repositories;

                if (! $pager->hasNext()) {
                    return;
                }

                $repositories = $pager->fetchNext();
            }
        })->filter(fn (array $repo) => $repo['private'] === false);
    }

    public function countPullRequests(string $userName, string $repoName): int
    {
        $pager = new ResultPager($this->client, 1);
        $pullRequests = $pager->fetch($this->client->api('pull_request'), 'all', [$userName, $repoName, ['state' => 'open']]);
        $this->ensureCompleteResponse();
        $pagination = ResponseMediator::getPagination($this->client->getLastResponse());

        if (isset($pagination['last'])) {
            parse_str(parse_url($pagination['last'], PHP_URL_QUERY), $parameters);

            if (isset($parameters['page']) && ctype_digit((string) $parameters['page']) && (int) $parameters['page'] > 0) {
                return (int) $parameters['page'];
            }
        }

        $count = count($pullRequests);

        while ($pager->hasNext()) {
            $pullRequests = $pager->fetchNext();
            $this->ensureCompleteResponse();
            $count += count($pullRequests);
        }

        return $count;
    }

    /**
     * GitHub keys contributor statistics by the default branch SHA. Reuse only
     * the count for that exact commit, checking for a moving branch on a miss.
     */
    public function countContributors(string $userName, string $repoName, string $defaultBranch): int
    {
        try {
            $sha = $this->defaultBranchSha($userName, $repoName, $defaultBranch);
        } catch (GitHubException $exception) {
            if (! in_array($exception->getCode(), [404, 409], true)) {
                throw $exception;
            }

            $count = $this->fetchContributorCount($userName, $repoName);

            if ($this->client->getLastResponse()->getStatusCode() !== 204) {
                throw $exception;
            }

            return $count;
        }

        $cacheKey = 'github-contributor-count:' . hash('sha256', "{$userName}/{$repoName}/{$defaultBranch}");
        $cached = Cache::get($cacheKey);

        if ($cached !== null && $cached['sha'] === $sha) {
            return $cached['count'];
        }

        $count = $this->fetchContributorCount($userName, $repoName);

        if ($this->defaultBranchSha($userName, $repoName, $defaultBranch) !== $sha) {
            throw new RuntimeException("The default branch changed while fetching contributors for {$userName}/{$repoName}.");
        }

        Cache::put($cacheKey, ['sha' => $sha, 'count' => $count], now()->addDay());

        return $count;
    }

    protected function ensureCompleteResponse(): void
    {
        if ($this->client->getLastResponse()->getStatusCode() !== 200) {
            throw new RuntimeException('GitHub returned an incomplete response.');
        }
    }

    protected function defaultBranchSha(string $userName, string $repoName, string $defaultBranch): string
    {
        $branch = $this->client->api('repo')->branches($userName, $repoName, $defaultBranch);
        $sha = $branch['commit']['sha'] ?? null;

        if (! is_string($sha) || $sha === '') {
            throw new RuntimeException("Missing default branch commit for {$userName}/{$repoName}.");
        }

        return $sha;
    }

    protected function fetchContributorCount(string $userName, string $repoName): int
    {
        $contributors = $this->client->api('repo')->statistics($userName, $repoName);
        $status = $this->client->getLastResponse()->getStatusCode();

        if ($status === 204) {
            return 0;
        }

        if ($status !== 200 || ! is_array($contributors) || ! array_is_list($contributors)) {
            throw new RuntimeException("Contributor statistics are unavailable for {$userName}/{$repoName} (HTTP {$status}).");
        }

        return count($contributors);
    }
}
