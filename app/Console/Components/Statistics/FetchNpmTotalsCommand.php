<?php

namespace App\Console\Components\Statistics;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Events\Statistics\NpmTotalsFetched;
use Developmint\NpmStats\NpmStats;

class FetchNpmTotalsCommand extends Command
{
    protected $signature = 'dashboard:fetch-npm-totals';

    protected $description = 'Fetch totals for all our JavaScript packages';

    public function handle()
    {
        $httpClient = new Client();

        $npmStats = new NpmStats($httpClient);

        $totals = $this->getPackageList()
            ->map(function ($packageName) use ($npmStats) {
                return [
                    'monthly' => $npmStats->getStats($packageName, NpmStats::LAST_MONTH)['downloads'],
                    'total' => $npmStats->getStats($packageName, NpmStats::TOTAL)['downloads'],
                ];
            })->pipe(function ($packageProperties) {
                return [
                    'monthly' => $packageProperties->sum('monthly'),
                    'total' => $packageProperties->sum('total'),
                ];
            });

        event(new NpmTotalsFetched($totals));
    }

    protected function getPackageList()
    {
        $packages = json_decode(file_get_contents('https://spatie.be/en/api/packages'));

        return collect($packages)
            ->filter(function ($package) {
                return $package->type === 'javascript';
            })
            ->map(function ($package) {
                return $package->name;
            });
    }
}
