<?php

namespace App\Mollie\Payments;

use App\Services\PaymentCreation\ValidatedCreatePaymentRequest;
use Money\Money;

class ApplicationFee
{

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    public function __construct(Money $amount, string $description)
    {
        $this->amount = $amount;
        $this->description = $description;
    }

    public static function fromValidatedRequest(ValidatedCreatePaymentRequest $validatedRequest): self
    {
        return new self($validatedRequest->applicationFeeAmount(), "hardcoded application fee description");
    }

    public function toArray(): array
    {
        return [
            'amount' => [
                'value' => money_to_string($this->amount),
                'currency' => $this->amount->getCurrency()->getCode()
            ],
            'description' => $this->description
        ];
    }
}