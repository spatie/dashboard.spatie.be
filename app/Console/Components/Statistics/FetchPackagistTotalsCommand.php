<?php

namespace App\Console\Components\Statistics;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use MarkWalet\Packagist\Facades\Packagist;
use App\Events\Statistics\PackagistTotalsFetched;

class FetchPackagistTotalsCommand extends Command
{
    protected $signature = 'dashboard:fetch-packagist-totals';

    protected $description = 'Fetch totals for all our PHP packages';

    public function handle()
    {
        $this->info('Fetching packagist totals...');

        $totals = collect(Packagist::getPackagesNamesByVendor(config('services.packagist.vendor'))['packageNames'])
                ->map(function (string $packageName) {
                    return Packagist::getPackage($packageName)['package'];
                })
                ->pipe(function (Collection $packageProperties) {
                    return [
                        'monthly' => $packageProperties->sum('downloads.monthly'),
                        'total' => $packageProperties->sum('downloads.total'),
                    ];
                });

        event(new PackagistTotalsFetched($totals));

        $this->info('All done!');
    }
}
