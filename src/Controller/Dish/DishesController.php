<?php

namespace App\Controller\Dish;

use App\Dishes\DishesProvider;
use App\Restaurants\RestaurantProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DishesController extends AbstractController
{

    private DishesProvider $dishesProvider;
    private RestaurantProvider $restaurantProvider;

    public function __construct(DishesProvider $dishesProvider, RestaurantProvider $restaurantProvider)
    {
        $this->dishesProvider = $dishesProvider;
        $this->restaurantProvider = $restaurantProvider;
    }

    /**
     * @Route("/store/restaurant/{restaurantId}", name="restaurant_dishes")
     */
    public function list(int $restaurantId): Response
    {
        $dishes = $this->dishesProvider->findAllForRestaurant($restaurantId);
        $restaurant = $this->restaurantProvider->getDetails($restaurantId);

        return $this->render('store/dishes/index.html.twig', ['dishes' => $dishes, 'restaurant' => $restaurant->toArray()]);
    }

}