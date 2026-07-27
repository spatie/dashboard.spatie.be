<?php

namespace App\Services\Fathom\Data;

class RankedRowsData
{
    /** @param array<int, array<string, mixed>> $rows */
    public function __construct(public array $rows)
    {
    }
}
