<?php

namespace Tests\Feature\Tiles\Statistics;

use Github\Client;
use Tests\TestCase;
use RuntimeException;
use GuzzleHttp\Psr7\Response;
use App\Services\GitHub\GitHubApi;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use App\Tiles\Statistics\StatisticsStore;
use MarkWalet\Packagist\Facades\Packagist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchStatisticsCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function testItSumsThePackagistDownloadsOfAllPackages(): void
    {
        config()->set('services.packagist.vendor', 'spatie');

        Packagist::shouldReceive('getPackagesNamesByVendor')
            ->once()
            ->andReturn(['packageNames' => ['spatie/laravel-backup', 'spatie/laravel-permission']]);

        Packagist::shouldReceive('getPackage')
            ->with('spatie/laravel-backup')
            ->andReturn(['package' => ['downloads' => ['monthly' => 10, 'total' => 100], 'versions' => []]]);

        Packagist::shouldReceive('getPackage')
            ->with('spatie/laravel-permission')
            ->andReturn(['package' => ['downloads' => ['monthly' => 5, 'total' => 50], 'versions' => []]]);

        $this->artisan('dashboard:fetch-packagist-totals')->assertSuccessful();

        $this->assertSame(15, StatisticsStore::make()->packagistMonthly());
        $this->assertSame(150, StatisticsStore::make()->packagistTotal());
    }

    public function testItSumsPublicRepositoriesAcrossPagesWithoutDownloadingEveryPullRequest(): void
    {
        $api = $this->fakeGitHub([
            ['/orgs/spatie/repos', ['type' => 'public', 'per_page' => '100', 'page' => '1'], $this->gitHubResponse([
                $this->repository('first', 10, 5),
                $this->repository('private', 999, 999, true),
            ], '<https://api.github.com/orgs/spatie/repos?page=2&per_page=100&type=public>; rel="next"')],
            ['/repos/spatie/first/pulls', ['state' => 'open', 'per_page' => '1'], $this->gitHubResponse(
                [['number' => 1]],
                '<https://api.github.com/repos/spatie/first/pulls?page=2&per_page=1>; rel="next", <https://api.github.com/repos/spatie/first/pulls?page=123&per_page=1>; rel="last"'
            )],
            ['/repos/spatie/first/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'first-sha']])],
            ['/repos/spatie/first/stats/contributors', [], $this->gitHubResponse([
                ['author' => ['login' => 'alice'], 'weeks' => []],
                ['author' => ['login' => 'bob'], 'weeks' => []],
            ])],
            ['/repos/spatie/first/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'first-sha']])],
            ['/orgs/spatie/repos', ['page' => '2', 'per_page' => '100', 'type' => 'public'], $this->gitHubResponse([
                $this->repository('second', 20, 7),
            ])],
            ['/repos/spatie/second/pulls', ['state' => 'open', 'per_page' => '1'], $this->gitHubResponse([])],
            ['/repos/spatie/second/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'second-sha']])],
            ['/repos/spatie/second/stats/contributors', [], $this->gitHubResponse([
                ['author' => ['login' => 'alice'], 'weeks' => []],
            ])],
            ['/repos/spatie/second/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'second-sha']])],
        ]);

        $this->app->instance(GitHubApi::class, $api);

        $this->artisan('dashboard:fetch-github-totals')->assertSuccessful();

        $this->assertSame([
            'stars' => 30,
            'issues' => 12,
            'pullRequests' => 123,
            'contributors' => 3,
        ], StatisticsStore::make()->gitHubTotals());
    }

    public function testContributorCountsAreReusedOnlyWhileTheDefaultBranchCommitIsUnchanged(): void
    {
        $api = $this->fakeGitHub([
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']]])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'new']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']], ['author' => ['login' => 'bob']]])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'new']])],
        ]);

        $this->assertSame(1, $api->countContributors('spatie', 'example', 'main'));
        $this->assertSame(1, $api->countContributors('spatie', 'example', 'main'));
        $this->assertSame(2, $api->countContributors('spatie', 'example', 'main'));
    }

    public function testItCountsEmptyRepositories(): void
    {
        $api = $this->fakeGitHub([
            ['/repos/spatie/empty/branches/main', [], $this->gitHubResponse(['message' => 'Not Found'], status: 404)],
            ['/repos/spatie/empty/stats/contributors', [], new Response(204)],
        ]);

        $this->assertSame(0, $api->countContributors('spatie', 'empty', 'main'));
    }

    public function testItCountsPullRequestsWhenGitHubOmitsTheLastPageLink(): void
    {
        $api = $this->fakeGitHub([
            ['/repos/spatie/example/pulls', ['state' => 'open', 'per_page' => '1'], $this->gitHubResponse(
                [['number' => 1]],
                '<https://api.github.com/repos/spatie/example/pulls?page=2&per_page=1>; rel="next"'
            )],
            ['/repos/spatie/example/pulls', ['page' => '2', 'per_page' => '1'], $this->gitHubResponse([['number' => 2]])],
        ]);

        $this->assertSame(2, $api->countPullRequests('spatie', 'example'));
    }

    public function testPendingContributorStatisticsDoNotReplaceThePreviouslyPublishedTotals(): void
    {
        $previousTotals = ['stars' => 30, 'issues' => 12, 'pullRequests' => 5, 'contributors' => 10];
        StatisticsStore::make()->setGitHubTotals($previousTotals);

        $api = $this->fakeGitHub([
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']]])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/orgs/spatie/repos', ['type' => 'public', 'per_page' => '100', 'page' => '1'], $this->gitHubResponse([$this->repository('example', 50, 20)])],
            ['/repos/spatie/example/pulls', ['state' => 'open', 'per_page' => '1'], $this->gitHubResponse([])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'new']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([], status: 202)],
        ]);
        $this->assertSame(1, $api->countContributors('spatie', 'example', 'main'));
        $this->app->instance(GitHubApi::class, $api);

        try {
            $this->artisan('dashboard:fetch-github-totals')->run();
            $this->fail('Pending contributor statistics must fail the refresh.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Contributor statistics are unavailable', $exception->getMessage());
        }

        $this->assertSame($previousTotals, StatisticsStore::make()->gitHubTotals());
    }

    public function testContributorCountsAreNotCachedWhenTheBranchChangesDuringTheRequest(): void
    {
        $api = $this->fakeGitHub([
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']]])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'new']])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']], ['author' => ['login' => 'bob']]])],
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['commit' => ['sha' => 'old']])],
        ]);

        try {
            $api->countContributors('spatie', 'example', 'main');
            $this->fail('A moving default branch must fail the refresh.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('default branch changed', $exception->getMessage());
        }

        $this->assertSame(2, $api->countContributors('spatie', 'example', 'main'));
    }

    public function testFailureOnALaterRepositoryPageDoesNotPublishPartialTotals(): void
    {
        $previousTotals = ['stars' => 30, 'issues' => 12, 'pullRequests' => 5, 'contributors' => 10];
        StatisticsStore::make()->setGitHubTotals($previousTotals);

        $api = $this->fakeGitHub([
            ['/orgs/spatie/repos', ['type' => 'public', 'per_page' => '100', 'page' => '1'], $this->gitHubResponse(
                [],
                '<https://api.github.com/orgs/spatie/repos?page=2&per_page=100&type=public>; rel="next"'
            )],
            ['/orgs/spatie/repos', ['page' => '2', 'per_page' => '100', 'type' => 'public'], $this->gitHubResponse(['message' => 'Rate limit exceeded'], status: 403)],
        ]);
        $this->app->instance(GitHubApi::class, $api);

        try {
            $this->artisan('dashboard:fetch-github-totals')->run();
            $this->fail('A failed repository page must fail the refresh.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Rate limit exceeded', $exception->getMessage());
        }

        $this->assertSame($previousTotals, StatisticsStore::make()->gitHubTotals());
    }

    public function testAMissingDefaultBranchIsNotSilentlyCountedAsAnEmptyRepository(): void
    {
        $api = $this->fakeGitHub([
            ['/repos/spatie/example/branches/main', [], $this->gitHubResponse(['message' => 'Not Found'], status: 404)],
            ['/repos/spatie/example/stats/contributors', [], $this->gitHubResponse([['author' => ['login' => 'alice']]])],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not Found');

        $api->countContributors('spatie', 'example', 'main');
    }

    protected function repository(string $name, int $stars, int $issues, bool $private = false): array
    {
        return ['name' => $name, 'stargazers_count' => $stars, 'open_issues' => $issues, 'private' => $private, 'default_branch' => 'main'];
    }

    protected function gitHubResponse(array $body, string $link = '', int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json', 'Link' => $link], json_encode($body, JSON_THROW_ON_ERROR));
    }

    protected function fakeGitHub(array $requests): GitHubApi
    {
        config()->set('services.github.username', 'spatie');
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(count($requests)))
            ->method('sendRequest')
            ->willReturnCallback(function (RequestInterface $request) use (&$requests) {
                [$path, $expectedQuery, $response] = array_shift($requests);
                $this->assertSame($path, $request->getUri()->getPath());
                parse_str($request->getUri()->getQuery(), $query);
                $this->assertEquals($expectedQuery, $query);

                return $response;
            });

        return new GitHubApi(Client::createWithHttpClient($httpClient));
    }

    public function testTheGitHubTotalsAreFetchedHourlyInTheBackgroundAwayFromOtherLongTasks(): void
    {
        $fetchEvent = $this->scheduledEvent('dashboard:fetch-github-totals');

        $this->assertSame('39 4-20 * * 1-5', $fetchEvent->expression);
        $this->assertTrue($fetchEvent->runInBackground);
    }

    public function testThePackagistTotalsAreFetchedHourlyAwayFromTheGitHubTotals(): void
    {
        $fetchEvent = $this->scheduledEvent('dashboard:fetch-packagist-totals');

        $this->assertSame('29 4-20 * * 1-5', $fetchEvent->expression);
    }
}
