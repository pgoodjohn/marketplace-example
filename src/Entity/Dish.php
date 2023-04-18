<?php

namespace App\Entity;

use App\Dishes\DishViewModel;
use App\Repository\DishRepository;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity(repositoryClass=DishRepository::class)
 */
class Dish
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $unit_amount_cents;

    /**
     * @ORM\Column(type="integer")
     */
    private $restaurant_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnitAmountCents(): ?int
    {
        return $this->unit_amount_cents;
    }

    public function setUnitAmountCents(int $unit_amount_cents): self
    {
        $this->unit_amount_cents = $unit_amount_cents;

        return $this;
    }

    public function getRestaurantId(): ?int
    {
        return $this->restaurant_id;
    }

    public function setRestaurantId(int $restaurant_id): self
    {
        $this->restaurant_id = $restaurant_id;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function toViewModel(): DishViewModel
    {
        return new DishViewModel(
            $this->id,
            $this->name,
            Money::EUR($this->unit_amount_cents),
            $this->image_url,
        );
    }
}
