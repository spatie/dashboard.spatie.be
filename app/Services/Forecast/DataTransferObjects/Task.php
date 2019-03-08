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
        $project = self::getProjectName($project, $attributes['notes'] ?? '');
        $name = self::getName($attributes['notes'] ?? '', $project);
        $hours = self::getHours($startDate, $endDate, $attributes['allocation'] ?? 0, $project);
        $formattedTime = self::formatTime($hours);

        return new Task([
            'id' => $attributes['id'],
            'name' => $name,
            'person_id' => $attributes['person_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'hours' => $hours,
            'formatted_time' => $formattedTime,
            'project' => $project,
        ]);
    }

    protected static function getProjectName(string $project, string $name): string
    {
        if ($project === 'Time Off') {
            return 'Verlof';
        }

        if ($project === 'Open source / Eigen werk' && !empty($name)) {
            return $name;
        }

        if ($project === 'Spatie Overhead' && !empty($name)) {
            return $name;
        }

        return $project;
    }

    protected static function getName(string $name, string $project): string
    {
        $projectSafeForRegex = str_replace('/', '\/', $project);
        $name = trim(preg_replace("/^{$projectSafeForRegex}\s+/", '', $name));

        if ($project === $name) {
            return '';
        }

        return $name;
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
