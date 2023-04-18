<?php

namespace App\Services\PaymentCreation;

use Money\Money;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\InputBag;
use function PHPUnit\Framework\assertMatchesRegularExpression;

class CreatePaymentRequestValidator
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function validate(InputBag $inputBag): ValidatedCreatePaymentRequest
    {
        $paymentAmountInput = $inputBag->get('paymentAmount');
        $applicationFeeAmountInput = $inputBag->get('applicationFeeAmount');

        assertMatchesRegularExpression('/^[0-9]*.{1}[0-9]{2}$/', $paymentAmountInput, "Payment amount not formatted properly");
        assertMatchesRegularExpression('/^[0-9]*.{1}[0-9]{2}$/', $applicationFeeAmountInput, "Application fee amount not formatted properly");

        $this->logger->debug("Create Payment Request was successfully validated, will proceed with payment creation");

        return new ValidatedCreatePaymentRequest(
            Money::EUR($applicationFeeAmountInput * 100),
            Money::EUR($paymentAmountInput * 100)
        );
    }

}