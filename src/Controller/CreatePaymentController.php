<?php

namespace App\Controller;

use App\Mollie\Payments\ApplicationFee;
use App\MolliePaymentsService;
use App\MollieUserManager;
use App\Services\PaymentCreation\CreatePaymentRequestValidator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreatePaymentController extends AbstractController
{
    /**
     * @var MollieUserManager
     */
    private $mollieUserManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var CreatePaymentRequestValidator
     */
    private $createPaymentRequestValidator;
    /**
     * @var MolliePaymentsService
     */
    private $molliePaymentsService;

    public function __construct(
        MolliePaymentsService $molliePaymentsService,
        MollieUserManager $mollieUserManager,
        LoggerInterface $logger,
        CreatePaymentRequestValidator $createPaymentRequestValidator
    ) {
        $this->molliePaymentsService = $molliePaymentsService;
        $this->mollieUserManager = $mollieUserManager;
        $this->logger = $logger;
        $this->createPaymentRequestValidator = $createPaymentRequestValidator;
    }

    /**
     * @Route("/merchant/mollie/createPayment", name="create_payment", methods={"POST"})
     */
    public function createPayment(Request $request): Response
    {
        $this->logger->debug("Starting payment creation");

        $user = $this->getUser();

        if ($user === null) {
            $this->logger->error("Could not find a logged in user to create the payment for, redirecting to login.");
            return $this->redirectToRoute('app_login');
        }

        try {
            $validatedRequest = $this->createPaymentRequestValidator->validate($request->request);
            $submerchantDetails = $this->mollieUserManager->getSubmerchantDetailsForLoggedInUser($user);

            $response = $this->molliePaymentsService->createPayment(
                $submerchantDetails,
                ApplicationFee::fromValidatedRequest($validatedRequest),
                $validatedRequest->paymentAmount()
            );

            // TODO Do something with the Mollie Response (i.e. show the checkout URL)
//            return new Response(json_encode($response, JSON_PRETTY_PRINT  | JSON_UNESCAPED_SLASHES));
            return $this->render('merchant/dashboard/payment.html.twig', [
                'paymentId' => $response['id'],
                'molliePaymentUrl' => $response['_links']['checkout']['href'],
            ]);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Something went wrong with creating a payment, got %s', $e->getMessage()));

            // TODO: Better error message handling
            return $this->redirectToRoute('merchant_dashboard', ['error' => '🚨 Could not reach Mollie to create a payment. Exception: ' . $e->getMessage()]);
        }

    }
}
