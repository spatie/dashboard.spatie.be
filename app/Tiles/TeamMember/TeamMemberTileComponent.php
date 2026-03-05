<?php

namespace App\Tiles\TeamMember;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class TeamMemberTileComponent extends BaseTileComponent
{
    public string $name = '';

    public string $avatar = '';

    public bool $isBirthday = false;

    public ?string $nickName = null;

    public function mount(string $name, string $avatar, string $birthday, ?string $nickName = null): void
    {
        $this->name = $name;

        $this->avatar = $avatar;

        $this->isBirthday = Carbon::createFromFormat('Y-m-d', $birthday)->isToday();

        $this->nickName = $nickName;
    }

    public function render(): View
    {
        $teamMember = TeamMemberStore::find($this->name);

        // This is needed because Apple Music doesn't tell us when a track was played.
        $nowPlaying = $teamMember->nowPlaying();
        $playlimit = $teamMember->nowPlaying()->durationInSeconds ?? 10 * 60;

        if ($nowPlaying && $teamMember->lastUpdate()?->diffInSeconds() > $playlimit) {
            $nowPlaying = null;
        }

        return view('components.tiles.teamMember', [
            'statusEmoji' => $teamMember->statusEmoji(),
            'artwork' => $nowPlaying?->artwork,
            'currentArtist' => $nowPlaying?->artist,
        ]);
    }
}
