<?php

namespace App\Entity;

use App\Repository\SubmissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Application::class)]
    #[ORM\JoinColumn(referencedColumnName: 'uuid', nullable: false)]
    private Application $application;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $created;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $data = [];

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    #[ORM\PrePersist]
    public function perPersist(): void
    {
        $this->created = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function setApplication(Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        array_walk_recursive($data, static function($item){return trim($item);});

        $this->data = $data;

        return $this;
    }
}
