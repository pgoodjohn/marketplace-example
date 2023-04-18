<?php

namespace App;

use App\Entity\MollieUser;
use App\Mollie\MollieHttpClient;
use App\Mollie\OAuthClient;
use App\Mollie\Payments\SubmerchantDetail;
use App\Repository\MollieUserRepository;
use League\OAuth2\Client\Token\AccessToken;
use PhpParser\Node\Scalar\String_;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function PHPUnit\Framework\assertArrayHasKey;

class MollieUserManager
{

    /**
     * @var MollieUserRepository
     */
    private $mollieUserRepository;

    /**
     * @var OAuthClient
     */
    private $OAuthClient;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MollieHttpClient
     */
    private $httpClient;

    public function __construct(MollieUserRepository $mollieUserRepository, OAuthClient $OAuthClient, LoggerInterface $logger, MollieHttpClient $httpClient)
    {
        $this->mollieUserRepository = $mollieUserRepository;
        $this->OAuthClient = $OAuthClient;
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    public function registerMollieMerchant(UserInterface $user, string $accessToken, string $refreshToken, string $mollieOrganizationId, string $mollieProfileId): void
    {
        $existingUser = $this->mollieUserRepository->findOneBy(['user_id' => $user->getId()]);

        if ($existingUser) {
            throw new \RuntimeException("Already have Mollie user for {$existingUser->getId()}");
        }


        $mollieUser = new MollieUser();
        $mollieUser->setUserId($user->getId());
        $mollieUser->setMollieAccessToken($accessToken);
        $mollieUser->setMollieRefreshToken($refreshToken);
        $mollieUser->setMollieOrganizationId($mollieOrganizationId);
        $mollieUser->setMollieProfileId($mollieProfileId);

        $this->mollieUserRepository->persist($mollieUser);
    }

    public function isUserAlreadyAMollieMerchant(UserInterface $user): bool
    {
        $mollieOrg = $this->mollieUserRepository->findOneBy(['user_id' => $user->getId()]);

        if ($mollieOrg) {
            return true;
        }

        return false;
    }

    public function getUserAccessToken(UserInterface $user): AccessToken
    {
        $mollieUser =  $this->getMollieUserDetail($user);

        return new AccessToken(['access_token'=> $mollieUser->getMollieAccessToken(), 'refresh_token' => $mollieUser->getMollieRefreshToken()]);
    }

    private function getMollieUserDetail(UserInterface $user): MollieUser
    {
        if (!$this->isUserAlreadyAMollieMerchant($user)) {
            throw new \LogicException("Can't get mollie user details for a user that has not yet linked its Mollie Account");
        }

        return $this->mollieUserRepository->findOneBy(['user_id' => $user->getId()]);
    }

    public function getSubmerchantDetailsForLoggedInUser(UserInterface $user): SubmerchantDetail
    {
        $this->logger->debug("Retrieving submerchant details for User {$user->getId()}");

        $mollieUserDetail = $this->getMollieUserDetail($user);

        $accessToken = $this->OAuthClient->getProvider()->getAccessToken('refresh_token', ['refresh_token' => $mollieUserDetail->getMollieRefreshToken()]);

        $this->logger->debug("User was successfully re-authenticated with Mollie, proceeding with payment creation");

        return new SubmerchantDetail($accessToken, $mollieUserDetail->getMollieProfileId());
    }

    public function findProfileId(\League\OAuth2\Client\Token\AccessTokenInterface $accessToken): string
    {
        $this->logger->debug("Retrieving profile id for Mollie User");

        $profilesResponse = $this->httpClient->getProfiles($accessToken);
        assertArrayHasKey("_embedded", $profilesResponse);

        $profilesObject = $profilesResponse['_embedded'];
        assertArrayHasKey("profiles", $profilesObject);
        $profilesList = $profilesObject["profiles"];

        return $profilesList[0]['id'];
    }
}