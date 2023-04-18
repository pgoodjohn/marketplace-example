<?php

namespace App\Services\PaymentCreation;

use Money\Money;

class ValidatedCreatePaymentRequest
{

    /**
     * @var Money
     */
    private $applicationFeeAmount;
    /**
     * @var Money
     */
    private $paymentAmount;

    public function __construct(Money $applicationFeeAmount, Money $paymentAmount)
    {
        $this->applicationFeeAmount = $applicationFeeAmount;
        $this->paymentAmount = $paymentAmount;
    }

    /**
     * @return Money
     */
    public function applicationFeeAmount(): Money
    {
        return $this->applicationFeeAmount;
    }

    /**
     * @return Money
     */
    public function paymentAmount(): Money
    {
        return $this->paymentAmount;
    }

}