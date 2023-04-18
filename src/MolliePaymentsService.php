<?php

namespace App;

use App\Mollie\MollieHttpClient;
use App\Mollie\Payments\ApplicationFee;
use App\Mollie\Payments\CreatePaymentRequest;
use App\Mollie\Payments\SubmerchantDetail;
use Money\Money;
use Psr\Log\LoggerInterface;

class MolliePaymentsService
{

    /**
     * @var MollieHttpClient
     */
    private $mollieHttpClient;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MollieHttpClient $mollieHttpClient, LoggerInterface $logger)
    {
        $this->mollieHttpClient = $mollieHttpClient;
        $this->logger = $logger;
    }

    public function createPayment(SubmerchantDetail $submerchantDetail, ApplicationFee $applicationFee, Money $amount): array
    {
        $request = new CreatePaymentRequest(
            $applicationFee,
            $amount,
            'https://example.com/?redirect=true',
            "Some test description",
            $submerchantDetail->profileId(),
            $submerchantDetail->accessToken()
        );

        $this->logger->debug("Request is ready, calling the Mollie API to create a payment");

        return $this->mollieHttpClient->createPayment($request);
    }

}