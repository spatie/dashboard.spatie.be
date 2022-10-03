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

        foreach (['GSENXMLW', 'LBABKDJB'] as $siteId) {
            $totals = Http::get("https://app.usefathom.com/sites/{$siteId}/boxes/totals?&tz=Europe/Brussels")
                ->json();

            $detailed = Http::get("https://app.usefathom.com/sites/{$siteId}/current_visitors/detailed")
                ->json();

            $timeOnSite = CarbonInterval::seconds($totals['content']['avg_time_on_site']['current'])
                ->cascade();

            FathomStore::find($siteId)->setStats([
                'current' => number_format($detailed['total']),
                'visitors' => number_format($totals['content']['visitors']['current']),
                'views' => number_format($totals['content']['views']['current']),
                'bounceRate' => number_format($totals['content']['bounce_rate']['current']) . '%',
                'eventCompletions' => number_format($totals['events']['completions']['current']),
                'avgTimeOnSite' => $timeOnSite->format('%I:%S')
            ]);
        }

        $this->info('All done!');
    }
}
