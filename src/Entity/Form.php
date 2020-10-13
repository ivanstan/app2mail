<?php

namespace App\Entity;

use App\Repository\FormRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormRepository::class)
 */
class Form
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $uuid;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $email = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $referer = [];

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

    public function getReferer(): ?array
    {
        return $this->referer;
    }

    public function setReferer(array $referer): self
    {
        $this->referer = $referer;

        return $this;
    }
}
