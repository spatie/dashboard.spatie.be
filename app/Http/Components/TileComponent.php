<?php

namespace App\Http\Components;

use Illuminate\View\Component;

class TileComponent extends Component
{
    public string $gridArea;

    public function __construct(string $position)
    {
        $this->gridArea = $this->convertToGridArea($position);
    }

    public function render()
    {
        return view('components.tile');
    }

    private function convertToGridArea(string $position): string
    {
        $parts = explode(':', $position);

        $from = $parts[0];
        $to = $parts[1] ?? null;

        if (strlen($from) < 2 || ($to && strlen($to) < 2)) {
            return '';
        }

        $areaFrom = "{$from[1]} / {$this->indexInAlphabet($from[0])}";

        if (! $to) {
            return $areaFrom;
        }

        $toStart = ((int)$to[1]) + 1;

        $toEnd = ((int)$this->indexInAlphabet($to[0])) + 1;

        return "{$areaFrom} / {$toStart} / {$toEnd}";

    }

    private function indexInAlphabet(string $character): int
    {
        $alphabet = range('a', 'z');

        $index = array_search(strtolower($character), $alphabet);

        return $index + 1;
    }
}
