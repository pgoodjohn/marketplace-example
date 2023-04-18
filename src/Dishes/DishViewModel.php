<?php

namespace App\Dishes;

use Money\Money;

class DishViewModel
{

    private int $id;
    private string $name;
    private Money $price;
    private ?string $imageUrl;

    public function __construct(
        int $id,
        string $name,
        Money $price,
        ?string $imageUrl
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => money_to_string($this->price),
            'imageUrl' => $this->imageUrl
        ];
    }

}