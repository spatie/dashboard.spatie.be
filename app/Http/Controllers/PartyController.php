<?php

namespace App\Http\Controllers;

use App\Services\Party\PartyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\JsonResponse;

class PartyController
{
    use JsonResponse;

    /** @var Party */
    private $parties;

    public function __construct(PartyRepository $respository) {
        $this->parties = $respository;
    }

    public function index()
    {
        return $this->jsonSuccess($this->parties->all()->toArray());
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $this->parties->insert($inputs);

        return $this->jsonSuccess('ok');
    }
}
