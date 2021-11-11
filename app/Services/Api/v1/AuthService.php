<?php

namespace App\Services\Api\v1;

use Illuminate\Support\Facades\Http;

class AuthService
{
    /**
     * Generate access token using client credential grant.
     *
     * @param array $inputs
     *
     * @return array|mixed
     */
    public function generateAccessToken(array $inputs)
    {
        $clientID = $inputs['client_id'];
        $clientSecret = $inputs['client_secret'];

        // Send request to oauth server.
        $response = Http::asForm()->post(config('app.url') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'scope' => '*'
        ]);

        return $response->json();
    }
}
