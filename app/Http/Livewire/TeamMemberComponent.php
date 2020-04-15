<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class TeamMemberComponent extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $position;

    /** @var string */
    public $avatar;

    /** @var bool */
    public $isToday;

    public function mounted(string $name, string $avatar, string $birthDay, string $position)
    {
        $this->name = $name;

        $this->avatar = $avatar;

        $this->position = $position;

        $this->isBirthday = Carbon::createFromFormat('Y-m-d', $birthDay)->isToday();
    }

    public function render()
    {
        return view('components.livewire.teamMember');
    }
}
