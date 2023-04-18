<?php

namespace App\Mollie;

use App\Mollie\Onboarding\OnboardingOrganizationDetails;
use App\Mollie\Onboarding\OnboardingStatus;
use App\Mollie\Payments\CreatePaymentRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use League\OAuth2\Client\Token\AccessTokenInterface;

class MollieHttpClient
{

    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            [
                'base_uri' => 'https://api.mollie.dev/',
                'timeout' => 10,
                'verify' => false
            ]
        );
    }

    public function createPayment(CreatePaymentRequest $createPaymentRequest): array
    {
        $request = new Request(
            'POST',
            '/v2/payments',
            [
                'Authorization' => 'Bearer ' . $createPaymentRequest->accessToken()->getToken(),
            ],
            json_encode($createPaymentRequest->toArray())
        );

        $response = $this->client->send($request);

        return json_decode($response->getBody()->__toString(), true);
    }

    public function getProfiles(AccessTokenInterface $accessToken): array
    {
        $request = new Request(
            'GET',
            '/v2/profiles',
            [
                'Authorization' => 'Bearer ' . $accessToken->getToken()
            ]
        );

        $response = $this->client->send($request);

        return json_decode($response->getBody()->__toString(), true);
    }

    public function getOnboardingStatus(AccessTokenInterface $accessToken): OnboardingStatus
    {
        $request = new Request(
            'GET',
            '/v2/onboarding/me',
            [
                'Authorization' => 'Bearer ' . $accessToken->gettoken()
            ]
        );

        $response = $this->client->send($request);

        return OnboardingStatus::fromMollieApiResponse(json_decode($response->getBody()->__toString(), true));
    }

    public function getOrganizationDetails(AccessTokenInterface $accessToken): array
    {
        $request = new Request(
            'GET',
            '/v2/organizations/me',
            [
                'Authorization' => 'Bearer ' . $accessToken->getToken()
            ]
        );

        $response = $this->client->send($request);

        return json_decode($response->getBody()->__toString(), true);
    }

    public function submitOrganizationDetails(OnboardingOrganizationDetails $details, AccessTokenInterface $accessToken): array
    {
        $request = new Request(
            'POST',
            '/v2/onboarding/me',
            [
                'Authorization' => 'Bearer ' . $accessToken->getToken(),
            ],
            json_encode(["organization" => $details->toArray()])
        );

        $this->client->send($request);

        return [];
    }

}