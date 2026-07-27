<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Livewire\ProductAnalyticsScreenComponent;

class ProductAnalyticsScreenTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        RateLimiter::clear('fathom:reports');
        config()->set('services.fathom.token', 'fathom-token');
        $this->travelTo(CarbonImmutable::parse('2026-04-13 12:00:00', 'Europe/Brussels'));
    }

    public function testItLoadsOnlyWhenItsScreenActivates(): void
    {
        $this->fakeSuccessfulReports();

        $component = Livewire::test(ProductAnalyticsScreenComponent::class, [
            'productName' => 'Mailcoach',
            'emoji' => '📯',
            'siteId' => 'SITEID',
            'screenName' => 'mailcoach',
        ]);

        Http::assertNothingSent();

        $component
            ->call('activate', 'flare')
            ->assertSet('active', false);

        Http::assertNothingSent();

        $component
            ->call('activate', 'mailcoach')
            ->assertSet('active', true)
            ->assertSet('unavailable.daily', false)
            ->assertSee('Daily visitors')
            ->assertSee('Forecast');

        Http::assertSentCount(4);
    }

    public function testItRefreshesOnlyLiveDataWhileActiveAndStopsWhenInactive(): void
    {
        $this->fakeSuccessfulReports();

        $component = Livewire::test(ProductAnalyticsScreenComponent::class, [
            'productName' => 'Mailcoach',
            'emoji' => '📯',
            'siteId' => 'SITEID',
            'screenName' => 'mailcoach',
        ])->call('activate', 'mailcoach');

        Http::assertSentCount(4);

        $this->travel(31)->seconds();

        $component->call('refreshLive', 'mailcoach');
        defer()->invoke();

        Http::assertSentCount(5);
        $liveRequests = collect(Http::recorded())
            ->map(fn (array $record) => $record[0])
            ->filter(fn (Request $request) => str_contains($request->url(), '/current_visitors?'));
        $this->assertCount(2, $liveRequests);

        $component
            ->call('deactivate', 'mailcoach')
            ->assertSet('active', false);

        $this->travel(31)->seconds();
        $component->call('refreshLive', 'mailcoach');

        Http::assertSentCount(5);
    }

    public function testOneFailedBlockDoesNotBlankSuccessfulBlocks(): void
    {
        Http::fake(function (Request $request) {
            if (isset($request['date_grouping']) && $request['date_grouping'] === 'day') {
                return Http::response([], 500);
            }

            if (str_contains($request->url(), '/current_visitors?')) {
                return Http::response(['total' => 2, 'content' => []]);
            }

            return Http::response([]);
        });

        Livewire::test(ProductAnalyticsScreenComponent::class, [
            'productName' => 'Mailcoach',
            'emoji' => '📯',
            'siteId' => 'SITEID',
            'screenName' => 'mailcoach',
        ])
            ->call('activate', 'mailcoach')
            ->assertSet('unavailable.daily', true)
            ->assertSet('unavailable.live', false)
            ->assertSet('live.total', 2)
            ->assertSee('Analytics unavailable')
            ->assertSee('Live visitors');
    }

    private function fakeSuccessfulReports(): void
    {
        Http::fake(function (Request $request) {
            if (str_contains($request->url(), '/current_visitors?')) {
                return Http::response(['total' => 2, 'content' => []]);
            }

            if (isset($request['date_grouping']) && $request['date_grouping'] === 'day') {
                return Http::response(['data' => [
                    ['timestamp' => '2026-04-06 00:00:00', 'visits' => 10, 'pageviews' => 12],
                    ['timestamp' => '2026-04-13 00:00:00', 'visits' => 4, 'pageviews' => 8],
                ]]);
            }

            return Http::response([]);
        });
    }
}
