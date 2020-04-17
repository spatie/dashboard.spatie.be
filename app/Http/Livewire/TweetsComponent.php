<?php

namespace App\Http\Livewire;

use App\Support\TweetStore;
use Livewire\Component;

class TweetsComponent extends Component
{
    /** @var string */
    public $position;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        return view('components.livewire.tweets', [
            'tweets' => TweetStore::make()->tweets(),
        ]);
    }
}
