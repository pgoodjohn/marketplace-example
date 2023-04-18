<?php

namespace App\Entity;

use App\Repository\RestaurantDetailRepository;
use App\Restaurants\RestaurantViewModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantDetailRepository::class)
 */
class RestaurantDetail
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
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /** @ORM\Column(type="integer")  */
    private $user_id;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function toViewModel(): RestaurantViewModel
    {
        return new RestaurantViewModel(
            $this->getId(),
            $this->getName(),
            $this->getAddress(),
            42,
            "Amsterdam",
            "https://example.com",
            "https://via.placeholder.com/150"
        );
    }
}
