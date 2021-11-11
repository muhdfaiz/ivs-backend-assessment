<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\v1\GenerateAccessTokenRequest;
use App\Services\Api\v1\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    /**
     * @var AuthService
     */
    protected AuthService $service;

    /**
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Generate access token using client credentials.
     *
     * @param GenerateAccessTokenRequest $request
     *
     * @return JsonResponse
     */
    public function accessToken(GenerateAccessTokenRequest $request): JsonResponse
    {
        $inputs = $request->all();

        $accessToken = $this->service->generateAccessToken($inputs);

        return $this->successResponse($accessToken, __('auth.access_token_success'));
    }
}
