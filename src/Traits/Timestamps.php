<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

trait Timestamps
{
    #[ORM\Column(nullable: false, options:['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $deletedAt = null;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function setDeletedAt(?DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }
}