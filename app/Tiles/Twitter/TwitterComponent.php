<?php

namespace App\Tiles\Twitter;

use App\Tiles\Twitter\TwitterStore;
use Livewire\Component;

class TwitterComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.tiles.twitter', [
            'tweets' => TwitterStore::make()->tweets(),
        ]);
    }
}
