<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Carbon\CarbonInterval;
use Carbon\CarbonImmutable;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Services\Fathom\FathomAnalytics;

class ProductAnalyticsScreenComponent extends Component
{
    public string $productName;

    public string $emoji;

    public string $siteId;

    public string $screenName;

    public bool $active = false;

    /** @var array<string, mixed>|null */
    public ?array $daily = null;

    /** @var array<string, mixed>|null */
    public ?array $live = null;

    /** @var array<string, mixed>|null */
    public ?array $pages = null;

    /** @var array<string, mixed>|null */
    public ?array $sources = null;

    /** @var array<string, bool> */
    public array $unavailable = [
        'daily' => false,
        'live' => false,
        'pages' => false,
        'sources' => false,
    ];

    public function mount(string $productName, string $emoji, string $siteId, string $screenName): void
    {
        $this->productName = $productName;
        $this->emoji = $emoji;
        $this->siteId = $siteId;
        $this->screenName = $screenName;
    }

    #[On('dashboard-screen-activated')]
    public function activate(string $screenName): void
    {
        if ($screenName !== $this->screenName) {
            return;
        }

        $this->active = true;
        $analytics = app(FathomAnalytics::class);

        $this->loadBlock('daily', fn () => $analytics->daily($this->siteId));
        $this->loadBlock('live', fn () => $analytics->live($this->siteId));
        $this->loadBlock('pages', fn () => $analytics->topPages($this->siteId));
        $this->loadBlock('sources', fn () => $analytics->trafficSources($this->siteId));
    }

    #[On('dashboard-screen-deactivated')]
    public function deactivate(string $screenName): void
    {
        if ($screenName === $this->screenName) {
            $this->active = false;
        }
    }

    #[On('dashboard-product-analytics-live-refresh')]
    public function refreshLive(string $screenName): void
    {
        if (! $this->active || $screenName !== $this->screenName) {
            return;
        }

        $analytics = app(FathomAnalytics::class);

        $this->loadBlock('live', fn () => $analytics->live($this->siteId));
    }

    /** @return array<string, mixed> */
    public function chart(): array
    {
        $days = $this->daily['days'] ?? [];
        $forecast = (int) ($this->daily['forecast']['value'] ?? 0);
        $width = 840;
        $height = 250;
        $left = 42;
        $right = 88;
        $top = 22;
        $bottom = 38;
        $plotWidth = $width - $left - $right;
        $plotHeight = $height - $top - $bottom;
        $values = array_map(fn (array $day): int => (int) $day['visits'], $days);
        $max = max([1, $forecast, ...$values]);
        $count = max(1, count($days) - 1);
        $coordinates = collect($values)
            ->map(function (int $value, int $index) use ($left, $plotWidth, $count, $top, $plotHeight, $max): array {
                $x = $left + (($plotWidth / $count) * $index);
                $y = $top + $plotHeight - (($value / $max) * $plotHeight);

                return [
                    'x' => round($x, 2),
                    'y' => round($y, 2),
                ];
            });
        $completedCoordinates = $coordinates->slice(0, max(0, $coordinates->count() - 1));
        $actualPoints = $completedCoordinates
            ->map(fn (array $coordinate): string => "{$coordinate['x']},{$coordinate['y']}")
            ->implode(' ');
        $markerX = $left + $plotWidth;
        $forecastY = $top + $plotHeight - (($forecast / $max) * $plotHeight);
        $currentCoordinate = $coordinates->last() ?? [
            'x' => $markerX,
            'y' => $top + $plotHeight,
        ];
        $forecastStartCoordinate = $completedCoordinates->last() ?? $currentCoordinate;
        $forecastPoints = "{$forecastStartCoordinate['x']},{$forecastStartCoordinate['y']} {$markerX},".round($forecastY, 2);
        $dateLabels = [];

        if ($days !== []) {
            $lastIndex = count($days) - 1;
            $dateLabelIndexes = collect(range(0, $lastIndex, 7))
                ->filter(fn (int $index): bool => $index === 0 || $index <= $lastIndex - 4)
                ->push($lastIndex)
                ->unique()
                ->values();
            $dateLabels = $dateLabelIndexes
                ->map(function (int $index) use ($days, $lastIndex, $left, $plotWidth, $count): array {
                    return [
                        'x' => round($left + (($plotWidth / $count) * $index), 2),
                        'label' => $index === $lastIndex
                            ? 'Today'
                            : CarbonImmutable::parse($days[$index]['date'])->format('d M'),
                        'anchor' => match ($index) {
                            0 => 'start',
                            $lastIndex => 'end',
                            default => 'middle',
                        },
                    ];
                })
                ->all();
        }

        return [
            'width' => $width,
            'height' => $height,
            'left' => $left,
            'right_x' => $left + $plotWidth,
            'top' => $top,
            'bottom_y' => $top + $plotHeight,
            'middle_y' => $top + ($plotHeight / 2),
            'actual_points' => $actualPoints,
            'forecast_points' => $forecastPoints,
            'marker_x' => $markerX,
            'forecast_y' => $forecastY,
            'forecast_label_y' => max($top + 12, min($top + $plotHeight - 6, $forecastY - 9)),
            'current_y' => $currentCoordinate['y'],
            'current_value' => $values[array_key_last($values)] ?? 0,
            'date_labels' => $dateLabels,
            'max' => $max,
            'middle' => (int) round($max / 2),
        ];
    }

    public function duration(float|int $seconds): string
    {
        return CarbonInterval::seconds((float) $seconds)->cascade()->format('%I:%S');
    }

    public function render(): View
    {
        return view('livewire.product-analytics-screen');
    }

    private function loadBlock(string $property, callable $callback): void
    {
        try {
            $this->{$property} = $callback();
            $this->unavailable[$property] = false;
        } catch (Throwable) {
            $this->unavailable[$property] = $this->{$property} === null;
        }
    }
}
