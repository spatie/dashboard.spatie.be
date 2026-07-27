<?php

namespace Tests\Feature\Services\Fathom;

use Tests\TestCase;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\Fathom\FathomAnalytics;
use Illuminate\Support\Facades\RateLimiter;

class FathomAnalyticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        RateLimiter::clear('fathom:reports');
        config()->set('services.fathom.token', 'fathom-token');
        $this->travelTo(CarbonImmutable::parse('2026-04-13 12:00:00', 'Europe/Brussels'));
    }

    public function testItDerivesMetricsGroupsRoutesSortsAndCachesBlocks(): void
    {
        Http::fake(function (Request $request) {
            if (str_contains($request->url(), '/current_visitors?')) {
                return Http::response([
                    'total' => 4,
                    'content' => [
                        ['pathname' => '/users/123', 'total' => 1],
                        ['pathname' => '/users/456', 'total' => 2],
                        ['pathname' => '/docs', 'total' => 1],
                    ],
                ]);
            }

            if (isset($request['date_grouping']) && $request['date_grouping'] === 'day') {
                return Http::response(['data' => [
                    [
                        'timestamp' => '2026-03-16 00:00:00',
                        'visits' => 100,
                        'pageviews' => 150,
                        'bounce_rate' => 40,
                        'avg_duration' => 70,
                    ],
                    [
                        'timestamp' => '2026-03-23 00:00:00',
                        'visits' => 100,
                        'pageviews' => 150,
                        'bounce_rate' => 40,
                        'avg_duration' => 70,
                    ],
                    [
                        'timestamp' => '2026-03-30 00:00:00',
                        'visits' => 100,
                        'pageviews' => 150,
                        'bounce_rate' => 40,
                        'avg_duration' => 70,
                    ],
                    [
                        'timestamp' => '2026-04-06 00:00:00',
                        'visits' => 100,
                        'pageviews' => 150,
                        'bounce_rate' => 40,
                        'avg_duration' => 70,
                    ],
                    [
                        'timestamp' => '2026-04-13 00:00:00',
                        'visits' => 40,
                        'pageviews' => 100,
                        'bounce_rate' => 25.5,
                        'avg_duration' => 92,
                    ],
                ]]);
            }

            if (isset($request['field_grouping']) && $request['field_grouping'] === 'pathname') {
                return Http::response([
                    ['pathname' => '/orders/123', 'pageviews' => 5],
                    ['pathname' => '/orders/456', 'pageviews' => 8],
                    ['pathname' => '/docs', 'pageviews' => 12],
                    ['pathname' => '/pricing', 'pageviews' => 3],
                    ['pathname' => '/contact', 'pageviews' => 2],
                    ['pathname' => '/about', 'pageviews' => 1],
                ]);
            }

            return Http::response(['data' => [
                ['referrer_source' => 'Google', 'visits' => 10, 'pageviews' => 12],
                ['referrer_source' => '', 'visits' => 6, 'pageviews' => 8],
            ]]);
        });

        $analytics = app(FathomAnalytics::class);

        $daily = $analytics->daily('SITEID');
        $live = $analytics->live('SITEID');
        $pages = $analytics->topPages('SITEID');
        $sources = $analytics->trafficSources('SITEID');

        $this->assertSame(40, $daily['today']['visits']);
        $this->assertSame(100, $daily['today']['pageviews']);
        $this->assertSame(2.5, $daily['today']['views_per_visit']);
        $this->assertSame(90, $daily['forecast']['value']);
        $this->assertSame(4, $live['total']);
        $this->assertSame(
            [
                ['label' => '/users/:id', 'value' => 3],
                ['label' => '/docs', 'value' => 1],
            ],
            $live['pages'],
        );
        $this->assertCount(4, $pages['rows']);
        $this->assertSame(['/orders/:id', '/docs'], array_column(array_slice($pages['rows'], 0, 2), 'label'));
        $this->assertSame(13, $pages['rows'][0]['value']);
        $this->assertSame('Google', $sources['rows'][0]['label']);
        $this->assertSame('Direct', $sources['rows'][1]['label']);
        $this->assertArrayHasKey('updated_at', $daily);
        $this->assertArrayHasKey('updated_at', $live);

        $analytics->daily('SITEID');
        $analytics->live('SITEID');
        $analytics->topPages('SITEID');
        $analytics->trafficSources('SITEID');

        Http::assertSentCount(4);
    }

    public function testViewsPerVisitIsZeroWithoutVisits(): void
    {
        Http::fake([
            '*/aggregations*' => Http::response(['data' => [
                [
                    'timestamp' => '2026-04-13 00:00:00',
                    'visits' => 0,
                    'pageviews' => 10,
                ],
            ]]),
        ]);

        $daily = app(FathomAnalytics::class)->daily('SITEID');

        $this->assertSame(0, $daily['today']['views_per_visit']);
    }

    public function testItReturnsStaleSuccessfulDataWhenARefreshFails(): void
    {
        Http::fake([
            '*/aggregations*' => Http::sequence()
                ->push(['data' => [
                    ['timestamp' => '2026-04-13 00:00:00', 'visits' => 10, 'pageviews' => 15],
                ]])
                ->pushStatus(429)
                ->pushStatus(429),
        ]);

        $analytics = app(FathomAnalytics::class);
        $fresh = $analytics->daily('SITEID');

        $this->travel(6)->minutes();

        $stale = $analytics->daily('SITEID');
        defer()->invoke();
        $stillStale = $analytics->daily('SITEID');

        $this->assertSame($fresh, $stale);
        $this->assertSame($fresh, $stillStale);
    }
}
