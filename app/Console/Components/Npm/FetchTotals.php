<?php

namespace App\Console\Components\Npm;

use App\Events\Npm\TotalsFetched;
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

        $packageList = $this->getPackageList();

        /*
         * First idea was to filter the non-scoped and scoped packages so at least the
         * scoped packages could've been processed by bulk. This would work, but the
         * allowed range for bulk is lower than normal, which would lead to wrong,
         * incomplete or weird data in some cases
         *
         */


        $totals = $packageList->map(function ($packageName) use ($npmStats) {
            return [
                'daily' => $npmStats->getStats($packageName)["downloads"],
                'monthly' => $npmStats->getStats($packageName, NpmStats::LAST_MONTH)["downloads"],
                'total' => $npmStats->getStats($packageName, NpmStats::TOTAL)["downloads"],
            ];
        })->pipe(function ($packageProperties) {
            return [
                'daily' => $packageProperties->sum('daily'),
                'monthly' => $packageProperties->sum('monthly'),
                'total' => $packageProperties->sum('total'),
            ];
        });

        event(new TotalsFetched($totals));
    }

    private function getPackageList()
    {
        return collect([
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
    }
}
