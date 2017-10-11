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
         */
        list($scoped, $nonScoped) = $packageList->partition(function ($package) {
            return strpos($package, "@") === 0;
        });

        $bulkString = substr($nonScoped->reduce(function ($bulkString, $package) {
            $bulkString .= "{$package},";

            return $bulkString;
        }, ""), 0, -1);


        $nonScopedTotals = [
            "daily" => collect($npmStats->getStats($bulkString))->sum("downloads"),
            "monthly" => collect($npmStats->getStats($bulkString, NpmStats::LAST_MONTH))->sum("downloads"),
            "total" => collect($npmStats->getStats($bulkString, NpmStats::LAST_YEAR))->sum("downloads"),
        ];

        $totals = $scoped->reduce(function ($carry, $package) use ($npmStats) {

            $carry["daily"] += $npmStats->getStats($package)["downloads"];
            $carry["monthly"] += $npmStats->getStats($package, NpmStats::LAST_MONTH)["downloads"];
            $carry["total"] += $npmStats->getStats($package, NpmStats::LAST_YEAR)["downloads"];

            return $carry;
        }, $nonScopedTotals);

        dd($totals);

        event(new TotalsFetched($totals));
    }
}
