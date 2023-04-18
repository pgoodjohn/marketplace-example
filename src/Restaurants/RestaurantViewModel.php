<?php

namespace App\Restaurants;

final class RestaurantViewModel
{

    private int $id;
    private string $name;
    private string $street;
    private string $postalCode;
    private string $city;
    private string $websiteUrl;
    private string $imageUrl;

    public function __construct(
        int $id,
        string $name,
        string $street,
        string $postalCode,
        string $city,
        string $websiteUrl,
        string $imageUrl
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->websiteUrl = $websiteUrl;
        $this->imageUrl = $imageUrl;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "street" => $this->street,
            "postalCode" =>  $this->postalCode,
            "city" => $this->city,
            "url" => $this->websiteUrl,
            "imageUrl" => $this->imageUrl,
            "dishesUrl" => "/store/restaurant/" . $this->id,
        ];
    }

}