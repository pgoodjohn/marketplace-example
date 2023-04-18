<?php

namespace App\Mollie\Payments;

use League\OAuth2\Client\Token\AccessToken;

class SubmerchantDetail
{

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var string
     */
    private $profileId;

    public function __construct(AccessToken $accessToken, string $profileId)
    {
        $this->accessToken = $accessToken;
        $this->profileId = $profileId;
    }

    public function accessToken(): AccessToken
    {
        return $this->accessToken;
    }

    public function profileId(): string
    {
        return $this->profileId;
    }
}