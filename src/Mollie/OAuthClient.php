<?php

namespace App\Mollie;

use App\Repository\MollieApplicationConfigurationRepository;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use League\OAuth2\Client\Provider\GenericProvider;

class OAuthClient
{
    /**
     * @var GenericProvider
     */
    private $provider;

    public function __construct(MollieApplicationConfigurationRepository $repository)
    {
        $appConfiguration = $repository->getLastConfiguredApplication();

        $guzzyClient = new Client([
            'defaults' => [
                RequestOptions::CONNECT_TIMEOUT => 5,
                RequestOptions::ALLOW_REDIRECTS => true
            ],
            RequestOptions::VERIFY => false,
        ]);

        $this->provider = new GenericProvider([
            'clientId' => $appConfiguration->getClientId(),
            'clientSecret' => $appConfiguration->getClientSecret(),
            'redirectUri' => $appConfiguration->getRedirectUrl(),
            'urlAuthorize' => "https://mollie.dev/oauth2/authorize",
            'urlAccessToken' => "https://api.mollie.dev/oauth2/tokens",
            'urlResourceOwnerDetails' => "https://api.mollie.dev/v2/organizations/me",
            'verify' => false
        ]);

        $this->provider->setHttpClient($guzzyClient);
    }

    /**
     * @return GenericProvider
     */
    public function getProvider(): GenericProvider
    {
        return $this->provider;
    }
}