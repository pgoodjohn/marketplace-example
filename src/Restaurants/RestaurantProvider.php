<?php

namespace App\Restaurants;

use App\Entity\RestaurantDetail;
use App\Repository\RestaurantDetailRepository;

final class RestaurantProvider
{
    private RestaurantDetailRepository $restaurantDetailRepository;

    public function __construct(RestaurantDetailRepository $restaurantDetailRepository)
    {
        $this->restaurantDetailRepository = $restaurantDetailRepository;
    }

    public function list(): array
    {
        $restaurants = [];

        $restaurantEntities = $this->restaurantDetailRepository->findAll();

        foreach ($restaurantEntities as $restaurantDetail) {
            $restaurants[] = $restaurantDetail->toViewModel()->toArray();
        }

        return $restaurants;
    }

    public function getDetails(int $restaurantId): RestaurantViewModel
    {
        /** @var RestaurantDetail $restaurant */
        $restaurant = $this->restaurantDetailRepository->find($restaurantId);

        if ($restaurant === null) {
            throw new \LogicException("Can't find restaurant with id {$restaurantId}");
        }

        return $restaurant->toViewModel();
    }

}