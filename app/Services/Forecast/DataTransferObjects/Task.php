<?php

namespace App\Services\Forecast\DataTransferObjects;

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class Task extends DataTransferObject
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $project;

    /** @var int */
    public $person_id;

    /** @var \Illuminate\Support\Carbon */
    public $start_date;

    /** @var \Illuminate\Support\Carbon */
    public $end_date;

    /** @var int */
    public $hours;

    /** @var string */
    public $formatted_time;

    public static function fromForecastAttributes(array $attributes, string $project): Task
    {
        $startDate = Carbon::parse($attributes['start_date']);
        $endDate = Carbon::parse($attributes['end_date']);
        $project = self::projectName($project);
        $hours = self::getHours($startDate, $endDate, $attributes['allocation'] ?? 0, $project);
        $formattedTime = self::formatTime($hours);

        return new Task([
            'id' => $attributes['id'],
            'name' => $attributes['notes'] ?? '',
            'person_id' => $attributes['person_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'hours' => $hours,
            'formatted_time' => $formattedTime,
            'project' => $project,
        ]);
    }

    protected static function projectName(string $project): string
    {
        if ($project === 'Time Off') {
            return 'Verlof';
        }

        return str_replace('Open source', 'OSS', $project);
    }

    protected static function getHours(
        Carbon $startDate,
        Carbon $endDate,
        int $allocation,
        string $project
    ): int {
        $days = $endDate->diffInDays($startDate) + 1;

        if ($project === 'Verlof') {
            return $days * 8;
        }

        return $allocation / 3600 * $days;
    }

    protected static function formatTime(int $hours): string
    {
        if ($hours < 8) {
            return "{$hours}h";
        }

        $days = $hours / 8;

        return "{$days}d";
    }
}
