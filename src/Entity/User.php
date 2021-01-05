<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private int $id;
    private string $email;
    private array $roles = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?array
    {
        return null;
    }

    public function getSalt(): ?array
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): ?array
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}