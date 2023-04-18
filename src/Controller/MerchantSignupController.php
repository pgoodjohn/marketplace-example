<?php


namespace App\Controller;


use App\Security\SorryCouldNotCreateUser;
use App\Security\UserRegisterer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MerchantSignupController extends AbstractController
{

    /**
     * @var UserRegisterer
     */
    private $registerer;

    public function __construct(UserRegisterer $registerer)
    {
        $this->registerer = $registerer;
    }

    /**
     * @Route("/merchant/signup", name="merchant_signup_page", methods={"GET"})
     */
    public function index(): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('merchant_dashboard');
         }

        return $this->render('merchant/signup.html.twig', ['error' => null]);
    }

    /**
     * @Route("/merchant/signup", name="merchant_signup", methods={"POST"})
     */
    public function signup(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        try {
            $this->registerer->register($email, $password);
        } catch (SorryCouldNotCreateUser $e) {
            return $this->render('merchant/signup.html.twig', ['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->render('merchant/signup.html.twig', ['error' => 'Unexpected: ' . $e->getMessage()]);
        }

        return $this->redirectToRoute('app_login');
    }
}