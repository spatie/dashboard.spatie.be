<?php

namespace App\Livewire;

use App\Models\Deploy;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DeployCheckerComponent extends Component
{
    public int $lastDeployId = 0;

    public function mount(): void
    {
        $this->lastDeployId = Deploy::latest('id')->value('id') ?? 0;
    }

    public function render(): View
    {
        if (Deploy::where('id', '>', $this->lastDeployId)->exists()) {
            $this->dispatch('deploy-detected');
        }

        return view('livewire.deploy-checker');
    }
}
