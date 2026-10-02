<?php

namespace Tests\Feature\Tiles\Statistics;

use App\Tiles\Statistics\StatisticsStore;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MarkWalet\Packagist\Facades\Packagist;
use Tests\TestCase;

class FetchStatisticsCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function testItSumsThePackagistDownloadsOfAllPackages(): void
    {
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

    public function testTheGitHubTotalsAreFetchedHourlyInTheBackgroundAwayFromOtherLongTasks(): void
    {
        $fetchEvent = $this->scheduledEvent('dashboard:fetch-github-totals');

        $this->assertSame('39 6-21 * * 1-5', $fetchEvent->expression);
        $this->assertTrue($fetchEvent->runInBackground);
    }

    public function testThePackagistTotalsAreFetchedHourlyAwayFromTheGitHubTotals(): void
    {
        $fetchEvent = $this->scheduledEvent('dashboard:fetch-packagist-totals');

        $this->assertSame('29 6-21 * * 1-5', $fetchEvent->expression);
    }

    private function scheduledEvent(string $command): Event
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn (Event $event) => str_contains($event->command, $command));

        $this->assertNotNull($event);

        return $event;
    }
}
