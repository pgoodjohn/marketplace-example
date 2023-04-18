<?php

namespace App\Controller;

use App\Mollie\OAuthApplicationConfiguration;
use App\Repository\MollieApplicationConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MollieAppConfigurationController extends AbstractController
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MollieApplicationConfigurationRepository
     */
    private $repository;

    public function __construct(LoggerInterface $logger, MollieApplicationConfigurationRepository $repository)
    {
        $this->logger = $logger;
        $this->repository = $repository;
    }

    /**
     * @Route("/config", name="config", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        if ($this->repository->hasConfiguredApplication() === false) {
            return $this->render('config/index.html.twig', [
                'configured' => false,
                'appConfiguration' => (OAuthApplicationConfiguration::emptyConfiguration())->toArray(),
                'updated' => false
            ]);
        }

        $appConfiguration = $this->repository->getLastConfiguredApplication();

        return $this->render('config/index.html.twig', [
            'configured' => true,
            'appConfiguration' => $appConfiguration->toArray(),
            'updated' => $request->get('updated') ? true : false
        ]);
    }

    /**
     * @Route("/config", name="update_app_config", methods={"POST"})
     */
    public function update(Request $request): Response
    {
        $this->logger->debug("Updating Mollie's app configuration");

        $newAppConfiguration = OAuthApplicationConfiguration::fromRequest($request);

        $this->repository->save($newAppConfiguration);

        return $this->redirectToRoute('config', ['updated' => true]);
    }
}
