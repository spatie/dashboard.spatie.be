<?php

namespace App\Livewire;

use App\Models\Deploy;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DeployCheckerComponent extends Component
{
    public string $loadedAt;

    public function mount(): void
    {
        $this->loadedAt = now()->toIso8601String();
    }

    public function render(): View
    {
        $newDeployExists = Deploy::where('created_at', '>', $this->loadedAt)->exists();

        if ($newDeployExists) {
            $this->dispatch('deploy-detected');
        }

        return view('livewire.deploy-checker');
    }
}
