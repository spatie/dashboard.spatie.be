<?php

namespace App\Services\Party;

use Spatie\Valuestore\Valuestore;
use App\Models\Party;

class PartyRepository
{
    public function insert(array $inputs) 
    {
        return Party::create([
            'name' => $inputs['name'],
            'host_by' => $inputs['admin'],
            'total' => $inputs['total'],
            'pax' => $inputs['pax'],
            'status' => Party::STATUS_ACTIVE,
        ]);
    }

    public function all()
    {
        return Party::active()->get();
    }
}
