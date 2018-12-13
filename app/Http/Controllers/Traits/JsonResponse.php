<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Response;

trait JsonResponse 
{
    public function jsonSuccess($data) {
        return response()->json([
            'error' => false,
            'status' => 200,
            'data' => $data,
        ]);
    }

    public function jsonError($data) {
        return response()->json([
            'error' => true,
            'status' => 500,
            'data' => null,
            'message' => 'Something went wrong'
        ]);
    }
}