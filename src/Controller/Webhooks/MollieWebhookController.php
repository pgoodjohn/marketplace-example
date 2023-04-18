<?php


namespace App\Controller\Webhooks;


use App\Entity\MollieUser;
use App\Mollie\OAuthClient;
use App\MollieUserManager;
use App\Repository\MollieUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MollieWebhookController extends AbstractController
{

    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var OAuthClient
     */
    private $authClient;
    /**
     * @var MollieUserManager
     */
    private $mollieUserManager;

    public function __construct(RequestStack $requestStack, OAuthClient $authClient, MollieUserManager $mollieUserRegisterer)
    {
        $this->requestStack = $requestStack;
        $this->authClient = $authClient;
        $this->mollieUserManager = $mollieUserRegisterer;
    }

    /**
     * @Route("/webhooks/mollie/", name="mollie_webhook")
     */
    public function index(Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $user = $this->getUser();

        $oauth2SessionState = $session->get('oauth2state');
        $requestState = $request->get('state');

        if ($requestState == null || $requestState !== $oauth2SessionState) {
            $session->remove('oauth2state');
            return $this->redirectToRoute("restaurant_dashboard");
        }

        if ($request->get('error')) {
            return $this->render('merchant/dashboard/error.html.twig', ['error' => $request->get('error_description')]);
        }

        $accessToken = $this->authClient->getProvider()->getAccessToken('authorization_code', ['code' => $request->get('code')]);

        try {
            $mollieOrganizationResource = $this->authClient->getProvider()->getResourceOwner($accessToken);
            $mollieProfileId = $this->mollieUserManager->findProfileId($accessToken);
            $this->mollieUserManager->registerMollieMerchant($user, $accessToken->getToken(), $accessToken->getRefreshToken(), $mollieOrganizationResource->getId(), $mollieProfileId);
        } catch (\Throwable $e) {
            return $this->render('merchant/dashboard/error.html.twig', ['error' => $e->getMessage()]);
        }

        if (in_array("ROLE_RESTAURANT", $user->getRoles())) {
            return $this->redirectToRoute("restaurant_dashboard");
        }

        return $this->redirectToRoute("merchant_dashboard", ["access_token" => $accessToken->getToken()]);
    }

}