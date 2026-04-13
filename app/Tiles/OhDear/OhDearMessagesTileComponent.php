<?php

namespace App\Tiles\OhDear;

use App\Models\OhDearMessage;
use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class OhDearMessagesTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        $groups = OhDearMessage::query()
            ->where('occurred_at', '>=', now()->subDay())
            ->get()
            ->groupBy('group_key')
            ->map(fn ($messages) => [
                'severity' => $messages->first()->severity,
                'title' => $messages->first()->title,
                'site' => $messages->first()->site,
                'count' => $messages->count(),
                'latest_at' => $messages->max('occurred_at'),
            ])
            ->sortBy([
                fn ($a, $b) => ($a['severity'] === 'error' ? 0 : 1) <=> ($b['severity'] === 'error' ? 0 : 1),
                fn ($a, $b) => $b['count'] <=> $a['count'],
            ])
            ->values();

        return view('components.tiles.ohDearMessages', [
            'groups' => $groups,
        ]);
    }
}
