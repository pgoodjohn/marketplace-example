<?php

namespace App\Mollie;

use App\Entity\MollieApplicationConfiguration;
use Symfony\Component\HttpFoundation\Request;

class OAuthApplicationConfiguration
{

    /**
     * @var string
     */
    private $redirectUrl;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $clientSecret;

    public function __construct(string $redirectUrl, string $clientId, string $clientSecret)
    {
        $this->redirectUrl = $redirectUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public static function fromEntity(MollieApplicationConfiguration $entity): self
    {
        return new self(
            $entity->getRedirectUrl(),
            $entity->getClientId(),
            $entity->getClientSecret()
        );
    }

    public static function fromRequest(Request $request): self
    {
        // TODO: Validate
        $redirectUrl = $request->get('redirectUrl');
        $clientSecret = $request->get('clientSecret');
        $clientId = $request->get('clientId');

        return new self(
            $redirectUrl,
            $clientId,
            $clientSecret
        );
    }

    public static function emptyConfiguration(): self
    {
        return new self(
            '',
            '',
            ''
        );
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function toArray(): array
    {
        return [
            'redirectUrl' => $this->redirectUrl,
            'clientId' => $this->clientId,
            'clientSecret'=> $this->clientSecret
        ];
    }

}