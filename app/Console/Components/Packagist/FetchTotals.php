<?php

namespace App\Console\Components\Packagist;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\Packagist\Packagist;
use App\Events\Packagist\TotalsFetched;

class FetchTotals extends Command
{
    protected $signature = 'dashboard:fetch-packagist-totals';

    protected $description = 'Fetch totals for all our PHP packages';

    public function handle()
    {
        $packagist = new Packagist(new Client());

        $totals = collect($packagist->getPackagesByVendor(config('services.packagist.vendor'))['packageNames'])
                ->map(function ($packageName) use ($packagist) {
                    return $packagist->findPackageByName($packageName)['package'];
                })
                ->pipe(function ($packageProperties) {
                    return [
                        'daily' => $packageProperties->sum('downloads.daily'),
                        'monthly' => $packageProperties->sum('downloads.monthly'),
                        'total' => $packageProperties->sum('downloads.total'),
                    ];
                });

        event(new TotalsFetched($totals));
    }
}
