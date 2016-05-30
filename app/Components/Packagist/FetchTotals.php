<?php

namespace App\Components\Packagist;

use App\Components\Packagist\Events\TotalsFetched;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\Packagist\Packagist;

class FetchTotals extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:packagist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the total amount of downloads of packages for a vendor.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        $packagist = new Packagist($client);

        $totals = collect($packagist->getPackagesByVendor('spatie')['packageNames'])
                ->map(function ($packageName) use ($packagist) {
                    return $packagist->findPackageByName($packageName)['package'];
                })
                ->pipe(function ($packageProperties) {
                    return [
                        'stars' => $packageProperties->sum('favers'),
                        'daily' => $packageProperties->sum('downloads.daily'),
                        'monthly' => $packageProperties->sum('downloads.monthly'),
                        'total' => $packageProperties->sum('downloads.total'),
                    ];
                });

        event(new TotalsFetched($totals));
    }
}
