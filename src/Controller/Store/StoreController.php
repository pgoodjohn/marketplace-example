<?php

namespace App\Controller\Store;

use App\Restaurants\RestaurantProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{

    private RestaurantProvider $restaurantProvider;

    public function __construct(RestaurantProvider $restaurantProvider)
    {
        $this->restaurantProvider = $restaurantProvider;
    }

    /**
     * @Route("/store", name="store")
     */
    public function listRestaurants(): Response
    {
        $restaurants = $this->restaurantProvider->list();

        return $this->render('store/index.html.twig', ['restaurants' => $restaurants]);
    }
}