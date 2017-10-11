<?php

namespace App\Console\Components\Npm;

use App\Events\Packagist\TotalsFetched;
use Developmint\NpmStats\NpmStats;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchTotals extends Command
{
    protected $signature = 'dashboard:fetch-npm-totals';

    protected $description = 'Fetch totals for all our NPM packages';

    public function handle()
    {
        $npmStats = new NpmStats(new Client());

        $packageList = collect([
            "spatie-scss",
            "form-backend-validation",
            "vue-save-state",
            "@spatie/blender-js",
            "npm-install-peers",
            "eslint-config-spatie",
            "vue-tabs-component",
            "@spatie/blender-css",
            "vue-expose-inject",
            "@spatie/blender-media",
            "vue-table-component",
            "@spatie/blender-content-blocks",
            "font-awesome-filetypes",
            "@spatie/attachment-uploader",
            "postcss-assign",
            "@spatie/scss",
            "spatie-dom"
        ]);

        /*
         * First idea was to filter the non-scoped and scoped packages so at least the
         * scoped packages could've been processed by bulk. This would work, but the
         * allowed range for bulk is lower than normal, which would lead to wrong,
         * incomplete or weird data in some cases
         *

        $packages = $packageList->filter(function ($package) {
            return strpos($package, "@") === 0;
        });

        $nonScoped = $packageList->diff($packages);

        $bulkString = substr($nonScoped->reduce(function ($bulkString, $package) {
            $bulkString .= "{$package},";

            return $bulkString;
        }, ""), 0, -1);

        $packages->push($bulkString);

        */


        //Let's take the most naive approach by now! But.. 3 calls per package, 16 packages -> 48 calls. Damn!

        $totals = $packageList->reduce(function ($carry, $package) use ($npmStats) {

            $carry["daily"] += $npmStats->getStats($package)["downloads"];
            $carry["monthly"] += $npmStats->getStats($package, NpmStats::LAST_MONTH)["downloads"];
            $carry["total"] += $npmStats->getStats($package, NpmStats::TOTAL)["downloads"];

            return $carry;
        }, ["daily" => 0, "monthly" => 0, "total" => 0]);

        event(new TotalsFetched($totals));
    }
}
