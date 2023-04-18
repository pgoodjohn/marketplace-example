<?php

namespace App\Controller\Restaurant;

use App\Mollie\MollieHttpClient;
use App\Mollie\Onboarding\OnboardingOrganizationDetails;
use App\Mollie\Onboarding\OrganizationAddress;
use App\MollieUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubmitOnboardingDataController extends AbstractController
{

    private MollieUserManager $mollieUserManager;
    private MollieHttpClient $mollieHttpClient;

    public function __construct(MollieUserManager $mollieUserManager, MollieHttpClient $mollieHttpClient)
    {
        $this->mollieUserManager = $mollieUserManager;
        $this->mollieHttpClient = $mollieHttpClient;
    }

    /**
     * @Route("/restaurant/submit", name="submit_onboarding_data", methods={"POST"})
     */
    public function submit(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute("app_login");
        }

        $organizationDetails = new OnboardingOrganizationDetails(
            $request->get('name'),
            OrganizationAddress::fromRequest($request),
            $request->get('registrationNumber'),
            $request->get('vatNumber'),
            $request->get('vatRegulation')
        );

        $accessToken = $this->mollieUserManager->getUserAccessToken($user);

        try {
            $this->mollieHttpClient->submitOrganizationDetails($organizationDetails, $accessToken);
        } catch (\Throwable $e) {
            dd($e);
        }

        return $this->redirectToRoute("restaurant_dashboard");
    }
}