<?php

namespace App\Mollie\Payments;

use League\OAuth2\Client\Token\AccessToken;
use Money\Money;

class CreatePaymentRequest
{

    /**
     * @var ApplicationFee
     */
    private $applicationFee;
    /**
     * @var Money
     */
    private $amount;
    /**
     * @var string
     */
    private $redirectUrl;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $profileId;
    /**
     * @var AccessToken
     */
    private $submerchantAccessToken;

    public function __construct(
        ApplicationFee $applicationFee,
        Money $amount,
        string $redirectUrl, // TODO: Do I pass this or keep it hardcoded in the constructor?
        string $description,
        string $submerchantProfileId,
        AccessToken $submerchantAccessToken
    ) {
        $this->applicationFee = $applicationFee;
        $this->amount = $amount;
        $this->redirectUrl = $redirectUrl;
        $this->description = $description;
        $this->profileId = $submerchantProfileId;
        $this->submerchantAccessToken = $submerchantAccessToken;
    }

    public function accessToken(): AccessToken
    {
        return $this->submerchantAccessToken;
    }

    public function toArray(): array
    {
        return [
            'applicationFee' => $this->applicationFee->toArray(),
            'amount' => [
                'value' => money_to_string($this->amount),
                'currency' => $this->amount->getCurrency()->getCode()
            ],
            'redirectUrl' => $this->redirectUrl,
            'description' => $this->description,
            'profileId' => $this->profileId,
        ];
    }

}