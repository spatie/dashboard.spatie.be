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

        // This is needed because Apple Music doesn't tell us when a track was played.
        $nowPlaying = $teamMember->lastUpdate()?->diffInMinutes() <= 5
            ? $teamMember->nowPlaying()
            : null;

        return view('components.tiles.teamMember', [
            'statusEmoji' => $teamMember->statusEmoji(),
            'artwork' => $nowPlaying?->artwork,
            'currentArtist' => $nowPlaying?->artist,
        ]);
    }
}
