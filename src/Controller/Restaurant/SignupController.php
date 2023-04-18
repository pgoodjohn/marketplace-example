<?php

namespace App\Controller\Restaurant;

use App\Entity\User;
use App\Security\SorryCouldNotCreateUser;
use App\Security\UserAuthenticator;
use App\Security\UserRegisterer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class SignupController extends AbstractController
{

    private UserRegisterer $registerer;

    public function __construct(UserRegisterer $registerer)
    {
        $this->registerer = $registerer;
    }

    /**
     * @Route("/restaurant/signup", name="restaurant_signup_page", methods={"GET"})
     */
    public function index(): Response
    {
        if ($user = $this->getUser()) {
            if (in_array("ROLE_RESTAURANT", $user->getRoles())) {
                return $this->redirectToRoute("restaurant_dashboard");
            }
            return $this->redirectToRoute("merchant_dashboard");
        }

        return $this->render('restaurant/signup.html.twig', ['error' => null]);
    }

    /**
     * @Route("/restaurant/signup", name="merchant_signup", methods={"POST"})
     */
    public function signup(Request $request): Response
    {
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        try {
            $this->registerer->registerRestaurant($email, $password);
        } catch (SorryCouldNotCreateUser $e) {
            return $this->render('restaurant/signup.html.twig', ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->render('restaurant/signup.html.twig', ['error' => 'Unexpected: ' . $e->getMessage()]);
        }

        return $this->redirectToRoute('app_login');
    }
}