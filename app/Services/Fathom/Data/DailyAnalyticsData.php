<?php

namespace App\Services\Fathom\Data;

class DailyAnalyticsData
{
    /** @param array<int, array<string, mixed>> $days */
    public function __construct(public array $days)
    {
    }
}
