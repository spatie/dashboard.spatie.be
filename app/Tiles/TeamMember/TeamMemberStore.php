<?php

namespace App\Tiles\TeamMember;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class TeamMemberStore
{
    private Valuestore $valuestore;

    public static function find(string $name): self
    {
        return new static($name);
    }

    public function __construct(string $name)
    {
        $path = storage_path("app/dashboard/teamMembers");

        File::makeDirectory($path, 0755, true, true);

        $this->valuestore = Valuestore::make("{$path}/{$name}.json");
    }

    public function setTasks(array $tasks): self
    {
        $this->valuestore->put('tasks', $tasks);

        return $this;
    }

    public function tasks(): array
    {
        $tasks = $this->valuestore->get('tasks') ?? [];

        [$longTasks, $shortTasks] = collect($tasks)->partition(function (array $task) {
            return $task['hours'] > 8;
        });

        return [
            'long' => $longTasks,
            'short' => $shortTasks,
        ];
    }

    public function setStatusEmoji(string $emoji): self
    {
        $this->valuestore->put('statusEmoji', $emoji);

        return $this;
    }

    public function statusEmoji(): string
    {
        return $this->valuestore->get('statusEmoji') ?? '';
    }

    public function setNowPlaying(array $track): self
    {
        $this->valuestore->put('nowPlaying', $track);

        return $this;
    }

    public function setNothingPlaying()
    {
        $this->valuestore->put('nowPlaying', []);

    }

    public function nowPlaying(): array
    {
        return $this->valuestore->get('nowPlaying') ?? [];
    }
}
