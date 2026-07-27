<?php

namespace App\Services\Fathom;

use Carbon\CarbonImmutable;

class ForecastCalculator
{
    /**
     * @param array<int, array{date: string, visits: int}> $days
     * @return array{
     *     value: int,
     *     typical: int,
     *     comparison_percent: ?int,
     *     remaining_fraction: float,
     * }
     */
    public function calculate(array $days, CarbonImmutable $now, string $timezone): array
    {
        $now = $now->setTimezone($timezone);
        $today = $now->toDateString();
        $completeDays = collect($days)->filter(
            fn (array $day) => $day['date'] < $today
        );
        $matchingWeekdays = $completeDays
            ->filter(fn (array $day) => CarbonImmutable::parse($day['date'], $timezone)->dayOfWeekIso === $now->dayOfWeekIso)
            ->sortByDesc('date')
            ->take(4);

        $baselineDays = $matchingWeekdays->isNotEmpty()
            ? $matchingWeekdays
            : $completeDays;

        $currentVisits = (int) (collect($days)->firstWhere('date', $today)['visits'] ?? 0);
        $typicalAverage = $baselineDays->isEmpty()
            ? $currentVisits
            : (float) $baselineDays->avg('visits');
        $typical = (int) round($typicalAverage);

        $startOfDay = $now->startOfDay();
        $endOfDay = $startOfDay->addDay();
        $dayLength = $endOfDay->getTimestamp() - $startOfDay->getTimestamp();
        $elapsed = $now->getTimestamp() - $startOfDay->getTimestamp();
        $remainingFraction = max(0, min(1, 1 - ($elapsed / $dayLength)));
        $forecast = $baselineDays->isEmpty()
            ? $currentVisits
            : (int) round($currentVisits + ($typicalAverage * $remainingFraction));

        $comparisonPercent = $typicalAverage > 0
            ? (int) round((($forecast - $typicalAverage) / $typicalAverage) * 100)
            : null;

        return [
            'value' => $forecast,
            'typical' => $typical,
            'comparison_percent' => $comparisonPercent,
            'remaining_fraction' => $remainingFraction,
        ];
    }
}
