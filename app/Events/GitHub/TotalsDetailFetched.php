<?php

namespace App\Events\GitHub;

use App\Events\DashboardEvent;
use Illuminate\Support\Collection;

class TotalsDetailFetched extends DashboardEvent
{
    /** @var Collection */
    public $totalDetails;

    public function __construct(Collection $totalsDetail)
    {
        $this->totalDetails = $totalsDetail;
    }
}
