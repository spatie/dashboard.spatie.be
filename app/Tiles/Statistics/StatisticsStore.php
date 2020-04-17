<?php

namespace App\Tiles\Statistics;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class StatisticsStore
{
    private Valuestore $valuestore;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $path = storage_path("app/dashboard");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/statistics.json");
    }

    public function setGitHubTotals(array $totals): self
    {
        $this->valuestore->put('gitHubTotals', $totals);

        return $this;
    }

    public function gitHubTotals(): array
    {
        return $this->valuestore->get('gitHubTotals') ?? [];
    }

    public function setPackagistTotals(array $totals): self
    {
        $this->valuestore->put('packagistTotals', $totals);

        return $this;
    }

    public function packagistTotals(): array
    {
        return $this->valuestore->get('packagistTotals') ?? [];
    }

    public function gitHubStars(): int
    {
        return $this->gitHubTotals()['stars'] ?? 0;
    }

    public function gitHubContributors(): int
    {
        return $this->gitHubTotals()['contributors'] ?? 0;
    }

    public function gitHubIssues(): int
    {
        return $this->gitHubTotals()['issues'] ?? 0;

    }

    public function gitHubPullRequests(): int
    {
        return $this->gitHubTotals()['pullRequests'] ?? 0;

    }

    public function packagistMonthly(): int
    {
        return $this->packagistTotals()['monthly'] ?? 0;
    }

    public function packagistTotal(): int
    {
        return $this->packagistTotals()['total'] ?? 0;

    }
}
