<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 */
class Application
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private string $uuid;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private ?array $email = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Recaptcha::class)
     */
    private ?Recaptcha $recaptcha;

    public function setUuid($uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getEmail(): ?array
    {
        return $this->email;
    }

    public function setEmail(?array $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRecaptcha(): ?Recaptcha
    {
        return $this->recaptcha;
    }

    public function setRecaptcha(?Recaptcha $recaptcha): self
    {
        $this->recaptcha = $recaptcha;

        return $this;
    }
}
