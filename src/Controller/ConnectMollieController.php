<?php


namespace App\Controller;

use App\Mollie\OAuthClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectMollieController extends AbstractController
{

    /**
     * @var OAuthClient
     */
    private $oauthProvider;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RequestStack    $requestStack,
        OAuthClient     $authClient,
        LoggerInterface $logger
    )
    {
        $this->oauthProvider = $authClient;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @Route("/merchant/mollie/connect", name="connect_mollie", methods={"GET"})
     */
    public
    function connectMollie(): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $scopes = [
            'organizations.read',
            'payments.write',
            'profiles.read',
            'onboarding.read',
            'onboarding.write'
        ];

        $authorizationUrl = $this->oauthProvider->getProvider()->getAuthorizationUrl([
            'approval_prompt' => 'force',
            'scope' => [
                implode(' ', $scopes)
            ]
        ]);

        $this->logger->info(sprintf('Calling autohorization url %s', $authorizationUrl));
        $this->requestStack->getSession()->set('oauth2state', $this->oauthProvider->getProvider()->getState());

        return $this->redirect($authorizationUrl);
    }

}
