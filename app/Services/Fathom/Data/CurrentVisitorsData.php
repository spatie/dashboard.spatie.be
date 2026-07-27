<?php

namespace App\Services\Fathom\Data;

class CurrentVisitorsData
{
    /** @param array<int, array<string, mixed>> $pages */
    public function __construct(
        public int $total,
        public array $pages,
    ) {
    }
}
