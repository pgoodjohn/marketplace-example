<?php

namespace App\Mollie\Onboarding;

use Symfony\Component\HttpFoundation\Request;

final class OrganizationAddress
{
    private string $street;
    private ?string $postalCode;
    private string $city;
    private string $country;

    public function __construct(
        string $street,
        ?string $postalCode,
        string $city,
        string $country
    )
    {
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->get('address_street'),
            $request->get('address_postalCode'),
            $request->get('address_city'),
            $request->get('address_country')
        );
    }

    public function toArray(): array
    {
        return [
            'streetAndNumber' => $this->street,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country
        ];
    }
}