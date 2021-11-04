<?php

namespace App\Http\Api;

use App\Models\Coffee;

class CoffeeController
{
    public function __invoke()
    {
        Coffee::create();

        return response()->noContent();
    }
}
