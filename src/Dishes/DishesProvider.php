<?php

namespace App\Dishes;

use App\Repository\DishRepository;

class DishesProvider
{

    private DishRepository $dishRepository;

    public function __construct(DishRepository $dishRepository)
    {
        $this->dishRepository = $dishRepository;
    }

    public function findAllForRestaurant(int $restaurantId): array
    {
        $dishes = [];

        foreach ($this->dishRepository->findBy(['restaurant_id' => $restaurantId]) as $dish) {
            $dishes[] = $dish->toViewModel()->toArray();
        }

        return $dishes;
    }
}