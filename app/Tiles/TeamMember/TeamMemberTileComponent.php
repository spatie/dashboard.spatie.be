<?php

namespace App\Tiles\TeamMember;

use Carbon\Carbon;
use Livewire\Component;

class TeamMemberTileComponent extends Component
{
    /** @var string */
    public $position;

    /** @var string */
    public $name;

    /** @var string */
    public $avatar;

    /** @var bool */
    public $isBirthday;

    /** @var string */
    public $nickName;

    public function mount(string $position, string $name, string $avatar, string $birthday, string $nickName = null)
    {
        $this->position = $position;

        $this->name = $name;

        $this->avatar = $avatar;

        $this->isBirthday = Carbon::createFromFormat('Y-m-d', $birthday)->isToday();

        $this->nickName = $nickName;
    }

    public function render()
    {
        $teamMember = TeamMemberStore::find($this->name);

        return view('components.tiles.teamMember', [
            //'tasks' => $teamMember->tasks(),
            'tasks' => $this->fakeTasks(),
            'hasTasks' => collect($teamMember->tasks())->flatten()->isNotEmpty(),
            'statusEmoji' => $teamMember->statusEmoji(),
            'artwork' => $teamMember->nowPlaying()['artwork'] ?? null,
            'currentTrack' => $teamMember->nowPlaying()['trackName'] ?? null,
        ]);
    }

    public function fakeTasks()
    {
        return [
            'long' => [
                ['project' => 'Dummy task', 'name' => '', 'formatted_time' => '2h'],
                ['project' => 'Another task', 'name' => '', 'formatted_time' => '3h'],
                ['project' => 'Yet another task', 'name' => '', 'formatted_time' => '4h']
            ],
            'short' => [],
        ];
    }
}
