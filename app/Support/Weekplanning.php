<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class Weekplanning
{
    public function isActive(?CarbonInterface $now = null): bool
    {
        $currentTime = $now
            ? CarbonImmutable::instance($now)->setTimezone($this->timezone())
            : CarbonImmutable::now($this->timezone());

        if ($currentTime->dayOfWeekIso !== $this->dayOfWeek()) {
            return false;
        }

        $start = $currentTime->startOfDay()->setTimeFromTimeString($this->startTime());
        $end = $currentTime->startOfDay()->setTimeFromTimeString($this->endTime());

        return $currentTime->greaterThanOrEqualTo($start) && $currentTime->lessThan($end);
    }

    public function refreshIntervalInSeconds(): int
    {
        return (int) config('dashboard.weekplanning.refresh_interval_in_seconds', 15);
    }

    protected function dayOfWeek(): int
    {
        return (int) config('dashboard.weekplanning.day_of_week', 1);
    }

    protected function startTime(): string
    {
        return (string) config('dashboard.weekplanning.start_time', '12:00');
    }

    protected function endTime(): string
    {
        return (string) config('dashboard.weekplanning.end_time', '12:15');
    }

    protected function timezone(): string
    {
        return (string) config('dashboard.weekplanning.timezone', config('app.timezone'));
    }
}
