<?php

namespace App\Tiles\Fathom\Commands;

use App\Tiles\Fathom\FathomStore;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchFathomStatistics extends Command
{
    protected $signature = 'dashboard:fetch-fathom-statistics';

    protected $description = 'Fetch totals for all our sites';

    public function handle(): void
    {
        $this->info('Fetching Fathom statistics...');

        $from = now()->timezone('Europe/Brussels')->startOfDay()->format('Y-m-d H:i:s');
        $to = now()->timezone('Europe/Brussels')->format('Y-m-d H:i:s');

        foreach (config('services.fathom.sites') as $siteId) {
            try {
                $this->fetchSiteStatistics($siteId, $from, $to);
            } catch (Throwable $e) {
                $this->error("Failed to fetch stats for {$siteId}: {$e->getMessage()}");
            }
        }

        $this->info('All done!');
    }

    private function fetchSiteStatistics(string $siteId, string $from, string $to): void
    {
        $current = $this->fathomRequest()
            ->get('https://api.usefathom.com/v1/current_visitors', [
                'site_id' => $siteId,
            ])
            ->json('total');

        $aggregations = $this->fathomRequest()
            ->get('https://api.usefathom.com/v1/aggregations', [
                'entity_id' => $siteId,
                'entity' => 'pageview',
                'aggregates' => 'visits,uniques,pageviews,avg_duration,bounce_rate',
                'timezone' => 'Europe/Brussels',
                'date_from' => $from,
                'date_to' => $to,
            ])->json()[0] ?? [];

        $timeOnSite = CarbonInterval::seconds($aggregations['avg_duration'] ?? 0)->cascade();

        FathomStore::find($siteId)->setStats([
            'current' => number_format($current),
            'visitors' => number_format($aggregations['uniques'] ?? 0),
            'views' => number_format($aggregations['pageviews'] ?? 0),
            'bounceRate' => number_format($aggregations['bounce_rate'] ?? 0).'%',
            'avgTimeOnSite' => $timeOnSite->format('%I:%S'),
        ]);
    }

    private function fathomRequest(): PendingRequest
    {
        return Http::withToken(config('services.fathom.token'))
            ->retry(
                times: 3,
                sleepMilliseconds: 2000,
                when: fn (Throwable $e) => $e instanceof RequestException && $e->response->status() === 429,
            );
    }
}
