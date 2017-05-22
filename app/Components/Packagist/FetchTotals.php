<?php

namespace App\Components\Packagist;

use App\Events\Packagist\TotalsFetched;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\Packagist\Packagist;

class FetchTotals extends Command
{
    protected $signature = 'dashboard:packagist';

    protected $description = 'Fetch the total amount of downloads of packages for a vendor.';

    public function handle()
    {
        $client = new Client();

        $packagist = new Packagist($client);

        $totals = collect($packagist->getPackagesByVendor(config('services.packagist.vendor'))['packageNames'])
                ->map(function ($packageName) use ($packagist) {
                    return $packagist->findPackageByName($packageName)['package'];
                })
                ->pipe(function ($packageProperties) {
                    return [
                        'daily'   => $packageProperties->sum('downloads.daily'),
                        'monthly' => $packageProperties->sum('downloads.monthly'),
                        'total'   => $packageProperties->sum('downloads.total'),
                    ];
                });

        event(new TotalsFetched($totals));
    }
}
