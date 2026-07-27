<?php

namespace Tests\Feature\Services\Fathom;

use Tests\TestCase;
use Carbon\CarbonImmutable;
use App\Services\Fathom\FathomApi;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class FathomApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.fathom.token', 'fathom-token');
        RateLimiter::clear('fathom:reports');
    }

    public function testItUsesVisitSemanticsAndNormalizesDocumentedAndRawResponses(): void
    {
        Http::fake([
            '*/aggregations*' => Http::sequence()
                ->push([
                    'object' => 'list',
                    'data' => [
                        [
                            'timestamp' => '2026-04-13 00:00:00',
                            'visits' => 12,
                            'pageviews' => 30,
                        ],
                    ],
                ])
                ->push([
                    ['pathname' => '/docs', 'visits' => 8, 'pageviews' => 10],
                ])
                ->push([
                    'data' => [
                        ['referrer_source' => 'Google', 'visits' => 7, 'pageviews' => 9],
                    ],
                ]),
            '*/current_visitors*' => Http::response([
                'data' => [
                    'total' => 3,
                    'content' => [
                        ['pathname' => '/docs', 'total' => 3],
                    ],
                ],
            ]),
        ]);

        $from = CarbonImmutable::parse('2026-03-15 00:00:00', 'Europe/Brussels');
        $to = CarbonImmutable::parse('2026-04-13 12:00:00', 'Europe/Brussels');
        $api = app(FathomApi::class);

        $daily = $api->dailyAnalytics('SITEID', $from, $to, 'Europe/Brussels');
        $current = $api->currentVisitors('SITEID');
        $pages = $api->topPages('SITEID', $to->startOfDay(), $to, 'Europe/Brussels');
        $sources = $api->trafficSources('SITEID', $to->startOfDay(), $to, 'Europe/Brussels');

        $this->assertSame(12, $daily->days[0]['visits']);
        $this->assertSame(3, $current->total);
        $this->assertSame('/docs', $pages->rows[0]['pathname']);
        $this->assertSame('Google', $sources->rows[0]['referrer_source']);

        Http::assertSent(function (Request $request): bool {
            if (! str_contains($request->url(), '/aggregations?')) {
                return false;
            }

            return $request['entity'] === 'pageview'
                && $request['entity_id'] === 'SITEID'
                && $request['aggregates'] === 'visits,pageviews,avg_duration,bounce_rate'
                && ! str_contains($request['aggregates'], 'uniques')
                && $request['date_grouping'] === 'day'
                && $request['timezone'] === 'Europe/Brussels';
        });

        Http::assertSent(function (Request $request): bool {
            return str_contains($request->url(), '/current_visitors?')
                && $request['site_id'] === 'SITEID'
                && $request['detailed'] === 'true';
        });

        $rankedRequests = collect(Http::recorded())
            ->map(fn (array $record) => $record[0])
            ->filter(fn (Request $request) => isset($request['field_grouping']));

        $this->assertCount(2, $rankedRequests);
        $this->assertTrue($rankedRequests->every(fn (Request $request) => $request['limit'] === 100));
    }

    public function testItRetriesTemporaryFailures(): void
    {
        Http::fake([
            '*/current_visitors*' => Http::sequence()
                ->pushStatus(429)
                ->push(['total' => 2, 'content' => []]),
        ]);

        $current = app(FathomApi::class)->currentVisitors('SITEID');

        $this->assertSame(2, $current->total);
        Http::assertSentCount(2);
    }
}
