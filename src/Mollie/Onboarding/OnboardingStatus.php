<?php

namespace App\Mollie\Onboarding;

final class OnboardingStatus
{

    private ?string $name;
    private \DateTimeImmutable $signedUpAt;
    private string $status;
    private bool $canReceivePayments;
    private bool $canReceiveSettlements;
    private string $hostedOnboardingUrl;

    public function __construct(
        ?string $name,
        \DateTimeImmutable $signedUpAt,
        string $status,
        bool $canReceivePayments,
        bool $canReceiveSettlements,
        string $hostedOnboardingUrl
    )
    {
        $this->name = $name;
        $this->signedUpAt = $signedUpAt;
        $this->status = $status;
        $this->canReceivePayments = $canReceivePayments;
        $this->canReceiveSettlements = $canReceiveSettlements;
        $this->hostedOnboardingUrl = $hostedOnboardingUrl;
    }

    public static function fromMollieApiResponse(array $response): self
    {
       return new self(
           $response['name'],
           new \DateTimeImmutable($response['signedUpAt']),
           $response['status'],
           $response['canReceivePayments'],
           $response['canReceiveSettlements'],
           $response['_links']['dashboard']['href']
       );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'signedUpAt' => $this->signedUpAt->format(\DateTimeImmutable::ATOM),
            'status' => $this->status,
            'canReceivePayments' => $this->canReceivePayments,
            'canReceiveSettlements' => $this->canReceiveSettlements,
            'hostedOnboardingUrl' => $this->hostedOnboardingUrl,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}