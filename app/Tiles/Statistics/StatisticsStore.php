<?php

namespace App\Tiles\Statistics;

use Spatie\Dashboard\Models\Tile;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\File;

class StatisticsStore
{
    private Tile $tile;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->tile = Tile::firstOrCreateForName('statistics');
    }

    public function setGitHubTotals(array $totals): self
    {
        $this->tile->putData('gitHubTotals', $totals);

        return $this;
    }

    public function gitHubTotals(): array
    {
        return $this->tile->getData('gitHubTotals') ?? [];
    }

    public function setPackagistTotals(array $totals): self
    {
        $this->tile->putData('packagistTotals', $totals);

        return $this;
    }

    public function packagistTotals(): array
    {
        return $this->tile->getData('packagistTotals') ?? [];
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
