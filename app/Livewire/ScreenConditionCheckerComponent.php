<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use App\Dashboard\ScreenAvailability;

class ScreenConditionCheckerComponent extends Component
{
    /** @var array<int, string> */
    public array $screenNames = [];

    /** @param array<int, string> $screenNames */
    public function mount(array $screenNames): void
    {
        $this->screenNames = $screenNames;
    }

    public function render(): View
    {
        $screens = collect(config('dashboard.screens', []))
            ->only($this->screenNames)
            ->filter(fn (mixed $screen): bool => is_array($screen))
            ->all();

        $availableScreenNames = app(ScreenAvailability::class)->availableScreenNames($screens);

        $this->dispatch(
            'dashboard-screen-availability-updated',
            availableScreenNames: $availableScreenNames,
        );

        return view('livewire.screen-condition-checker');
    }
}
