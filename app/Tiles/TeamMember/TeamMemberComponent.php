<?php

namespace App\Tiles\TeamMember;

use App\Tiles\TeamMember\TeamMemberStore;
use Carbon\Carbon;
use Livewire\Component;
use Spatie\Valuestore\Valuestore;

class TeamMemberComponent extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $position;

    /** @var string */
    public $avatar;

    /** @var bool */
    public $isBirthday;

    /** @var string */
    public $nickName;

    public function mount(string $position, string $name, string $avatar, string $birthday, string $nickName = null)
    {
        $this->name = $name;

        $this->avatar = $avatar;

        $this->position = $position;

        $this->isBirthday = Carbon::createFromFormat('Y-m-d', $birthday)->isToday();

        $this->nickName = $nickName;
    }

    public function render()
    {
        $teamMember = TeamMemberStore::find($this->name);

        return view('components.livewire.teamMember', [
            'tasks' => $teamMember->tasks(),
            'statusEmoji' => $teamMember->statusEmoji(),
            'artwork' => $teamMember->nowPlaying()['artwork'] ?? null,
            'currentTrack' => $teamMember->nowPlaying()['trackName'] ?? null,
        ]);
    }
}
