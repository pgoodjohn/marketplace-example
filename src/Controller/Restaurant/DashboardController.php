<?php

namespace App\Controller\Restaurant;

use App\Mollie\MollieHttpClient;
use App\Mollie\OAuthClient;
use App\MollieUserManager;
use App\Repository\RestaurantDetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private OAuthClient $oauthClient;
    private MollieUserManager $mollieUserManager;
    private RestaurantDetailRepository $restaurantDetailRepository;
    private MollieHttpClient $mollieClient;

    public function __construct(
        OAuthClient $oauthClient,
        MollieUserManager $mollieUserManager,
        RestaurantDetailRepository $restaurantDetailRepository,
        MollieHttpClient $mollieClient
    )
    {
        $this->oauthClient = $oauthClient;
        $this->mollieUserManager = $mollieUserManager;
        $this->restaurantDetailRepository = $restaurantDetailRepository;
        $this->mollieClient = $mollieClient;
    }

    /**
     * @Route("/restaurant", name="restaurant_dashboard")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $isMollieMerchant = $this->mollieUserManager->isUserAlreadyAMollieMerchant($this->getUser());
        $hasRestaurantDetails = $this->restaurantDetailRepository->findByUserId($user->getId());

        $error = null;
        if ($request->get('error')) {
            $error = $request->get('error');
        }

        if ($isMollieMerchant === false) {
            return $this->render(
                "restaurant/dashboard/index.html.twig",
                [
                    "is_mollie_merchant" => false,
                    "has_restaurant_details" => $hasRestaurantDetails !== null,
                    "onboarding_data" => null,
                    "organization_data" => null,
                    'error' => $error
                ]
            );
        }

        $accessToken = $this->mollieUserManager->getUserAccessToken($user);
        $onboardingData = $this->mollieClient->getOnboardingStatus($accessToken);
        $organizationData = $this->mollieClient->getOrganizationDetails($accessToken);

        return $this->render(
            "restaurant/dashboard/index.html.twig",
            [
                "is_mollie_merchant" => $isMollieMerchant,
                "has_restaurant_details" => $hasRestaurantDetails !== null,
                "onboarding_data" => $onboardingData->__toString(),
                "onboarding_status" => $onboardingData->toArray(),
                "organization_data" => $organizationData,
                "hosted_onboarding_url" => "",
                'error' => $error
            ]
        );
    }
}