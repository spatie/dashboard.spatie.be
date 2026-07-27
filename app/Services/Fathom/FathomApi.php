<?php

namespace App\Services\Fathom;

use RuntimeException;
use Carbon\CarbonInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\Fathom\Data\RankedRowsData;
use Illuminate\Http\Client\ConnectionException;
use App\Services\Fathom\Data\DailyAnalyticsData;
use App\Services\Fathom\Data\CurrentVisitorsData;

class FathomApi
{
    private const ApiUrl = 'https://api.usefathom.com/v1';

    private const RateLimitKey = 'fathom:reports';

    public function dailyAnalytics(
        string $siteId,
        CarbonInterface $from,
        CarbonInterface $to,
        string $timezone,
    ): DailyAnalyticsData {
        $response = $this->get('aggregations', [
            'entity' => 'pageview',
            'entity_id' => $siteId,
            'aggregates' => 'visits,pageviews,avg_duration,bounce_rate',
            'date_grouping' => 'day',
            'sort_by' => 'timestamp:asc',
            'timezone' => $timezone,
            'date_from' => $from->format('Y-m-d H:i:s'),
            'date_to' => $to->format('Y-m-d H:i:s'),
        ]);

        $days = collect($this->rows($response))
            ->map(function (array $row): ?array {
                $date = $row['date'] ?? $row['timestamp'] ?? null;

                if (! is_string($date)) {
                    return null;
                }

                return [
                    ...$row,
                    'date' => substr($date, 0, 10),
                ];
            })
            ->filter()
            ->values()
            ->all();

        return new DailyAnalyticsData($days);
    }

    public function currentVisitors(string $siteId): CurrentVisitorsData
    {
        $response = $this->get('current_visitors', [
            'site_id' => $siteId,
            'detailed' => 'true',
        ]);

        $payload = $this->payload($response);

        return new CurrentVisitorsData(
            total: (int) ($payload['total'] ?? 0),
            pages: is_array($payload['content'] ?? null)
                ? array_values(array_filter($payload['content'], is_array(...)))
                : [],
        );
    }

    public function topPages(
        string $siteId,
        CarbonInterface $from,
        CarbonInterface $to,
        string $timezone,
    ): RankedRowsData {
        return new RankedRowsData(
            $this->aggregationRows($siteId, $from, $to, $timezone, 'pathname', 'pageviews')
        );
    }

    public function trafficSources(
        string $siteId,
        CarbonInterface $from,
        CarbonInterface $to,
        string $timezone,
    ): RankedRowsData {
        return new RankedRowsData(
            $this->aggregationRows($siteId, $from, $to, $timezone, 'referrer_source', 'visits')
        );
    }

    /** @return array<int, array<string, mixed>> */
    private function aggregationRows(
        string $siteId,
        CarbonInterface $from,
        CarbonInterface $to,
        string $timezone,
        string $fieldGrouping,
        string $sortBy,
    ): array {
        $response = $this->get('aggregations', [
            'entity' => 'pageview',
            'entity_id' => $siteId,
            'aggregates' => 'visits,pageviews',
            'field_grouping' => $fieldGrouping,
            'sort_by' => "{$sortBy}:desc",
            'timezone' => $timezone,
            'date_from' => $from->format('Y-m-d H:i:s'),
            'date_to' => $to->format('Y-m-d H:i:s'),
            'limit' => 100,
        ]);

        return $this->rows($response);
    }

    /** @param array<string, mixed> $query */
    private function get(string $endpoint, array $query): Response
    {
        $lastResponse = null;
        $lastException = null;

        foreach ([1, 2] as $attempt) {
            try {
                $lastResponse = RateLimiter::attempt(
                    self::RateLimitKey,
                    10,
                    fn () => Http::baseUrl(self::ApiUrl)
                        ->acceptJson()
                        ->withToken((string) config('services.fathom.token'))
                        ->connectTimeout(2)
                        ->timeout(5)
                        ->get($endpoint, $query),
                    60,
                );

                if ($lastResponse === false) {
                    throw new RuntimeException('The local Fathom report rate limit was reached.');
                }

                if ($lastResponse->successful()) {
                    return $lastResponse;
                }

                if (! $this->shouldRetry($lastResponse) || $attempt === 2) {
                    return $lastResponse->throw();
                }
            } catch (ConnectionException $exception) {
                $lastException = $exception;

                if ($attempt === 2) {
                    throw $exception;
                }
            }

            usleep(200_000);
        }

        throw $lastException ?? new RuntimeException('Fathom did not return a response.');
    }

    private function shouldRetry(Response $response): bool
    {
        return $response->status() === 429 || $response->serverError();
    }

    /** @return array<string, mixed> */
    private function payload(Response $response): array
    {
        $payload = $response->json();

        if (! is_array($payload)) {
            return [];
        }

        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        return $payload;
    }

    /** @return array<int, array<string, mixed>> */
    private function rows(Response $response): array
    {
        $payload = $this->payload($response);

        if (! array_is_list($payload)) {
            return [];
        }

        return array_values(array_filter($payload, is_array(...)));
    }
}
