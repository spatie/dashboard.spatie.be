<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class Weekplanning
{
    public function isActive(?CarbonInterface $now = null): bool
    {
        $currentTime = $this->resolveCurrentTime($now);

        if ($currentTime->dayOfWeekIso !== $this->dayOfWeek()) {
            return false;
        }

        $start = $this->startDateTimeForDay($currentTime);
        $end = $this->endDateTimeForDay($currentTime);

        return $currentTime->greaterThanOrEqualTo($start) && $currentTime->lessThan($end);
    }

    public function millisecondsUntilNextTransition(?CarbonInterface $now = null): int
    {
        $currentTime = $this->resolveCurrentTime($now);
        $start = $this->nextStartDateTime($currentTime);
        $end = $this->endDateTimeForDay($currentTime);

        if ($this->isActive($currentTime)) {
            return $currentTime->diffInMilliseconds($end, false);
        }

        return $currentTime->diffInMilliseconds($start, false);
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

    protected function resolveCurrentTime(?CarbonInterface $now = null): CarbonImmutable
    {
        return $now
            ? CarbonImmutable::instance($now)->setTimezone($this->timezone())
            : CarbonImmutable::now($this->timezone());
    }

    protected function startDateTimeForDay(CarbonImmutable $date): CarbonImmutable
    {
        return $date->startOfDay()->setTimeFromTimeString($this->startTime());
    }

    protected function endDateTimeForDay(CarbonImmutable $date): CarbonImmutable
    {
        return $date->startOfDay()->setTimeFromTimeString($this->endTime());
    }

    protected function nextStartDateTime(CarbonImmutable $currentTime): CarbonImmutable
    {
        $daysUntilWeekplanning = ($this->dayOfWeek() - $currentTime->dayOfWeekIso + 7) % 7;

        $start = $this->startDateTimeForDay($currentTime->addDays($daysUntilWeekplanning));

        if ($daysUntilWeekplanning === 0 && $currentTime->greaterThanOrEqualTo($this->endDateTimeForDay($currentTime))) {
            return $start->addWeek();
        }

        return $start;
    }
}
