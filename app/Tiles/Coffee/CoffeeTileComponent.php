<?php

namespace App\Tiles\Coffee;

use App\Models\Coffee;
use Carbon\Carbon;
use Livewire\Component;

class CoffeeTileComponent extends Component
{
    /** @var string */
    public $position;

    protected int $totalOffset = 15_000;

    public function mount(string $position)
    {
        $this->position = $position;
    }

    public function render()
    {
        $dayCounts = Coffee::query()
            ->selectRaw('count(*) as count, date(created_at) as date')
            ->whereDate('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        $days = collect(range(13, 0))
            ->map(fn(int $day) => now()->subDays($day)->format('Y-m-d'))
            ->map(fn(string $date) => $dayCounts[$date] ?? 0);

        return view('components.tiles.coffee', [
            'today' => Coffee::query()->whereDate('created_at', today())->count(),
            'thisWeek' => Coffee::query()->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count(),
            'total' => Coffee::count() + $this->totalOffset,
            'days' => $days,
            'daysMax' => $days->max(),
        ]);
    }
}
