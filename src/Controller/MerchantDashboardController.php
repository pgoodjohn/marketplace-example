<?php


namespace App\Controller;

use App\Mollie\OAuthClient;
use App\MollieUserManager;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MerchantDashboardController extends AbstractController
{

    /**
     * @var OAuthClient
     */
    private $authClient;
    /**
     * @var MollieUserManager
     */
    private $mollieUserRegisterer;

    public function __construct(OAuthClient $authClient, MollieUserManager $mollieUserRegisterer)
    {
        $this->authClient = $authClient;
        $this->mollieUserRegisterer = $mollieUserRegisterer;
    }

    /**
     * @Route("/merchant", name="merchant_dashboard")
     */
    public function index(Request $request): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $isMollieMerchant = $this->mollieUserRegisterer->isUserAlreadyAMollieMerchant($this->getUser());

        $error = null;
        if ($request->get('error')) {
            $error = $request->get('error');
        }

        return $this->render("merchant/dashboard/index.html.twig", ["is_mollie_merchant" => $isMollieMerchant, 'error' => $error]);
    }

}