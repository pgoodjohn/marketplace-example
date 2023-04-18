<?php

namespace App\Entity;

use App\Repository\MollieUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MollieUserRepository::class)
 */
class MollieUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mollie_access_token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mollie_refresh_token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mollie_organization_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mollie_profile_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getMollieAccessToken(): ?string
    {
        return $this->mollie_access_token;
    }

    public function setMollieAccessToken(string $mollie_access_token): self
    {
        $this->mollie_access_token = $mollie_access_token;

        return $this;
    }

    public function getMollieRefreshToken(): ?string
    {
        return $this->mollie_refresh_token;
    }

    public function setMollieRefreshToken(string $mollie_refresh_token): self
    {
        $this->mollie_refresh_token = $mollie_refresh_token;

        return $this;
    }

    public function getMollieOrganizationId(): string
    {
        return $this->mollie_organization_id;
    }

    public function setMollieOrganizationId(string $mollie_organization_id): self
    {
        $this->mollie_organization_id = $mollie_organization_id;

        return $this;
    }

    public function setMollieProfileId(string $string)
    {
        $this->mollie_profile_id = $string;
    }

    public function getMollieProfileId(): string
    {
        return $this->mollie_profile_id;
    }
}
