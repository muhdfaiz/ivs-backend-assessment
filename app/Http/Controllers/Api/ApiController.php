<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    /**
     * Generate success response
     *
     * @param $data
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'Successful'): JsonResponse
    {
        $result = [
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ];

        return response()->json($result, 200);
    }
}
