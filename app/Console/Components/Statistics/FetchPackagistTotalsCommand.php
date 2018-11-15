<?php

namespace App\Console\Components\Statistics;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\Packagist\Packagist;
use App\Events\Statistics\PackagistTotalsFetched;

class FetchPackagistTotalsCommand extends Command
{
    protected $signature = 'dashboard:fetch-packagist-totals';

    protected $description = 'Fetch totals for all our PHP packages';

    public function handle()
    {
        $this->info('Fetching packagist totals...');

        $packagist = new Packagist(new Client());

        $totals = collect($packagist->getPackagesByVendor(config('services.packagist.vendor'))['packageNames'])
                ->map(function ($packageName) use ($packagist) {
                    return $packagist->findPackageByName($packageName)['package'];
                })
                ->pipe(function ($packageProperties) {
                    return [
                        'monthly' => $packageProperties->sum('downloads.monthly'),
                        'total' => $packageProperties->sum('downloads.total'),
                    ];
                });

        event(new PackagistTotalsFetched($totals));

        $this->info('All done!');
    }
}
