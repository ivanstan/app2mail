<?php

namespace App\Entity;

use App\Repository\RecaptchaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecaptchaRepository::class)
 */
class Recaptcha
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $siteKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $secretKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteKey(): ?string
    {
        return $this->siteKey;
    }

    public function setSiteKey(string $siteKey): self
    {
        $this->siteKey = $siteKey;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;

        return $this;
    }
}
