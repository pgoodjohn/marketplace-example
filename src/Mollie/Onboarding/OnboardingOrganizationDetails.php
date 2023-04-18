<?php

namespace App\Mollie\Onboarding;

final class OnboardingOrganizationDetails
{
    private ?string $name;
    private ?string $registrationNumber;
    private ?string $vatNumber;
    private ?string $vatRegulation;
    private ?OrganizationAddress $address;

    public function __construct(
        ?string $name,
        ?OrganizationAddress $address,
        ?string $registrationNumber,
        ?string $vatNumber,
        ?string $vatRegulation
    )
    {
        $this->name = $name;
        $this->registrationNumber = $registrationNumber;
        $this->address = $address;
        $this->vatNumber = $vatNumber;
        $this->vatRegulation = $vatRegulation;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address ? $this->address->toArray() : null,
            'registrationNumber' => $this->registrationNumber,
            'vatNumber' => $this->vatNumber,
            'vatRegulation' => $this->vatRegulation,
        ];
    }

}