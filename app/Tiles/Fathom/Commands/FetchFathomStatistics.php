<?php

namespace App\Tiles\Fathom\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Tiles\Fathom\FathomStore;
use Illuminate\Support\Facades\Http;
use MarkWalet\Packagist\Facades\Packagist;

class FetchFathomStatistics extends Command
{
    protected $signature = 'dashboard:fetch-fathom-statistics';

    protected $description = 'Fetch totals for all our sites';

    public function handle()
    {
        $this->info('Fetching Fathom statistics...');

        $from = now()->timezone('Europe/Brussels')->startOfDay()->format('Y-m-d H:i:s');
        $to = now()->timezone('Europe/Brussels')->format('Y-m-d H:i:s');

        foreach (config('services.fathom.sites') as $siteId) {
            $current = Http::withToken(config('services.fathom.token'))
                ->get('https://api.usefathom.com/v1/current_visitors', [
                    'site_id' => $siteId,
                ])
                ->json('total');

            $aggregations = Http::withToken(config('services.fathom.token'))
                ->get('https://api.usefathom.com/v1/aggregations', [
                    'entity_id' => $siteId,
                    'entity' => 'pageview',
                    'aggregates' => 'visits,uniques,pageviews,avg_duration,bounce_rate',
                    'timezone' => 'Europe/Brussels',
                    'date_from' => $from,
                    'date_to' => $to,
                ])->json()[0];

            $events = Http::withToken(config('services.fathom.token'))
                ->get("https://api.usefathom.com/v1/sites/{$siteId}/events")
                ->json('data');

            $eventCompletions = collect($events)->mapWithKeys(function ($eventData) use ($to, $from) {
                $eventsCompleted = Http::withToken(config('services.fathom.token'))
                    ->get('https://api.usefathom.com/v1/aggregations', [
                        'entity_id' => $eventData['id'],
                        'entity' => 'event',
                        'aggregates' => 'conversions',
                        'timezone' => 'Europe/Brussels',
                        'date_from' => $from,
                        'date_to' => $to,
                    ])->json()[0];

                return [$eventData['id'] => [
                    'name' => $eventData['name'],
                    'completions' => number_format($eventsCompleted['conversions']),
                ]];
            })->toArray();

            $timeOnSite = CarbonInterval::seconds($aggregations['avg_duration'])->cascade();

            FathomStore::find($siteId)->setStats([
                'current' => number_format($current),
                'visitors' => number_format($aggregations['uniques']),
                'views' => number_format($aggregations['pageviews']),
                'bounceRate' => number_format($aggregations['bounce_rate'] * 100) . '%',
                'eventCompletions' => $eventCompletions,
                'avgTimeOnSite' => $timeOnSite->format('%I:%S')
            ]);
        }

        $this->info('All done!');
    }
}
