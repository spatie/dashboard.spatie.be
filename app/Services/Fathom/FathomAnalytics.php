<?php

namespace App\Services\Fathom;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

class FathomAnalytics
{
    public const Timezone = 'Europe/Brussels';

    private const CacheFormatVersion = 'v1';

    public function __construct(
        private FathomApi $api,
        private ForecastCalculator $forecastCalculator,
        private PathNormalizer $pathNormalizer,
    ) {
    }

    /** @return array<string, mixed> */
    public function daily(string $siteId): array
    {
        $now = CarbonImmutable::now(self::Timezone);

        return $this->remember(
            siteId: $siteId,
            queryType: 'daily',
            date: $now->toDateString(),
            freshFor: 300,
            staleFor: 1800,
            callback: function () use ($siteId, $now): array {
                $from = $now->startOfDay()->subDays(29);
                $analytics = $this->api->dailyAnalytics($siteId, $from, $now, self::Timezone);
                $rowsByDate = collect($analytics->days)
                    ->filter(fn (array $row) => filled($row['timestamp'] ?? null))
                    ->keyBy(fn (array $row) => substr((string) $row['timestamp'], 0, 10));

                $days = collect(range(29, 0))
                    ->map(function (int $daysAgo) use ($now, $rowsByDate): array {
                        $date = $now->subDays($daysAgo)->toDateString();
                        $row = $rowsByDate->get($date, []);

                        return [
                            'date' => $date,
                            'visits' => (int) ($row['visits'] ?? 0),
                            'pageviews' => (int) ($row['pageviews'] ?? 0),
                            'bounce_rate' => (float) ($row['bounce_rate'] ?? 0),
                            'avg_duration' => (float) ($row['avg_duration'] ?? 0),
                        ];
                    })
                    ->values()
                    ->all();

                $today = collect($days)->firstWhere('date', $now->toDateString()) ?? [
                    'visits' => 0,
                    'pageviews' => 0,
                    'bounce_rate' => 0,
                    'avg_duration' => 0,
                ];
                $visits = (int) $today['visits'];
                $pageviews = (int) $today['pageviews'];
                $forecastDays = $rowsByDate
                    ->map(fn (array $row, string $date): array => [
                        'date' => $date,
                        'visits' => (int) ($row['visits'] ?? 0),
                    ])
                    ->reject(fn (array $day) => $day['date'] === $now->toDateString())
                    ->push([
                        'date' => $now->toDateString(),
                        'visits' => $visits,
                    ])
                    ->values()
                    ->all();

                return [
                    'days' => $days,
                    'today' => [
                        ...$today,
                        'views_per_visit' => $visits === 0 ? 0 : round($pageviews / $visits, 2),
                    ],
                    'forecast' => $this->forecastCalculator->calculate($forecastDays, $now, self::Timezone),
                ];
            },
        );
    }

    /** @return array<string, mixed> */
    public function live(string $siteId): array
    {
        $now = CarbonImmutable::now(self::Timezone);

        return $this->remember(
            siteId: $siteId,
            queryType: 'live',
            date: $now->toDateString(),
            freshFor: 30,
            staleFor: 120,
            callback: function () use ($siteId): array {
                $visitors = $this->api->currentVisitors($siteId);

                return [
                    'total' => $visitors->total,
                    'pages' => $this->groupRows($visitors->pages, 'pathname', 'total'),
                ];
            },
        );
    }

    /** @return array<string, mixed> */
    public function topPages(string $siteId): array
    {
        $now = CarbonImmutable::now(self::Timezone);

        return $this->remember(
            siteId: $siteId,
            queryType: 'pages',
            date: $now->toDateString(),
            freshFor: 900,
            staleFor: 3600,
            callback: function () use ($siteId, $now): array {
                $pages = $this->api->topPages($siteId, $now->startOfDay(), $now, self::Timezone);

                return ['rows' => $this->groupRows($pages->rows, 'pathname', 'pageviews')];
            },
        );
    }

    /** @return array<string, mixed> */
    public function trafficSources(string $siteId): array
    {
        $now = CarbonImmutable::now(self::Timezone);

        return $this->remember(
            siteId: $siteId,
            queryType: 'sources',
            date: $now->toDateString(),
            freshFor: 900,
            staleFor: 3600,
            callback: function () use ($siteId, $now): array {
                $sources = $this->api->trafficSources($siteId, $now->startOfDay(), $now, self::Timezone);

                return ['rows' => $this->groupRows($sources->rows, 'referrer_source', 'visits', 'Direct')];
            },
        );
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array{label: string, value: int}>
     */
    private function groupRows(array $rows, string $labelKey, string $valueKey, string $emptyLabel = '/'): array
    {
        return collect($rows)
            ->map(function (array $row) use ($labelKey, $valueKey, $emptyLabel): array {
                $label = trim((string) ($row[$labelKey] ?? ''));
                $label = $label === '' ? $emptyLabel : $label;

                if (str_starts_with($label, '/') || str_contains($label, '://')) {
                    $label = $this->pathNormalizer->normalize($label);
                }

                return [
                    'label' => $label,
                    'value' => (int) ($row[$valueKey] ?? $row['visits'] ?? $row['total'] ?? 0),
                ];
            })
            ->groupBy('label')
            ->map(fn ($rows, string $label): array => [
                'label' => $label,
                'value' => $rows->sum('value'),
            ])
            ->sortByDesc('value')
            ->take(4)
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function remember(
        string $siteId,
        string $queryType,
        string $date,
        int $freshFor,
        int $staleFor,
        callable $callback,
    ): array {
        $timezone = rawurlencode(self::Timezone);
        $key = "fathom:".self::CacheFormatVersion.":{$siteId}:{$queryType}:{$date}:{$timezone}";

        return Cache::flexible(
            $key,
            [$freshFor, $staleFor],
            fn (): array => [
                ...$callback(),
                'updated_at' => CarbonImmutable::now(self::Timezone)->toIso8601String(),
            ],
            lock: ['seconds' => 10],
        );
    }
}
