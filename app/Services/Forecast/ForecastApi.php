<?php

namespace App\Services\Forecast;

use App\Services\Forecast\DataTransferObjects\Task;
use App\Services\Forecast\DataTransferObjects\Person;
use function GuzzleHttp\json_decode;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ForecastApi
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getThisWeeksTasksFor(array $names): Collection
    {
        $people = $this->getPeople()->keyBy('name');
        $tasks = $this->getThisWeeksTasks();

        return collect($names)
            ->map(function (string $name) use ($people) {
                return $people->get($name);
            })
            ->filter()
            ->mapWithKeys(function (Person $person) use ($tasks) {
                return [
                    $person->name => $tasks
                        ->where('person_id', $person->id)
                        ->sort(function (Task $a, Task $b) {
                            if ($a->project === "Verlof") {
                                return 1;
                            }

                            if ($a->project === "Open source / Eigen werk" && empty($a->name)) {
                                return 1;
                            }

                            if ($a->start_date->eq($b->start_date)) {
                                return $b->hours <=> $a->hours;
                            }

                            return $a->start_date <=> $b->start_date;
                        })
                        ->values()
                        ->map->toArray(),
                ];
            });
    }

    protected function getPeople(): Collection
    {
        $response = $this->client->get('people');

        $people = json_decode($response->getBody(), true)['people'];

        return collect($people)
            ->map(function (array $attributes) {
                return Person::fromForecastAttributes($attributes);
            });
    }

    protected function getThisWeeksTasks(): Collection
    {
        $thisWeek = Carbon::now()->startOfWeek();
        $nextWeek = Carbon::now()->startOfWeek()->addWeek();

        return $this->getTasks()
            ->filter(function (Task $assignment) use ($thisWeek, $nextWeek) {
                return $assignment->start_date->gte($thisWeek)
                    && $assignment->end_date->lt($nextWeek);
            });
    }

    protected function getTasks(): Collection
    {
        $projects = $this->getProjects()->keyBy('id');

        $response = $this->client->get('assignments');

        $assignments = json_decode($response->getBody(), true)['assignments'];

        return collect($assignments)
            ->filter(function (array $attributes) {
                return $attributes['person_id'];
            })
            ->map(function (array $attributes) use ($projects) {
                return Task::fromForecastAttributes(
                    $attributes,
                    $projects[$attributes['project_id']]['name'] ?? ''
                );
            });
    }

    protected function getProjects(): Collection
    {
        $response = $this->client->get('projects');

        $projects = json_decode($response->getBody(), true)['projects'];

        return collect($projects);
    }
}
