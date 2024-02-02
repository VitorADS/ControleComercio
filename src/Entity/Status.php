<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Status extends AbstractEntity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private bool $new = false;

    #[ORM\Column]
    private bool $finished = false;

    #[ORM\Column(options: ['default' => false])]
    private bool $ready = false;

    #[ORM\ManyToMany(targetEntity: Purchase::class, inversedBy: 'statuses')]
    private Collection $purchase;

    public function __construct()
    {
        $this->purchase = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isNew(): bool
    {
        return $this->new;
    }

    public function setNew(bool $new = false): self
    {
        $this->new = $new;
        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished = false): self
    {
        $this->finished = $finished;
        return $this;
    }

    public function isReady(): bool
    {
        return $this->ready;
    }

    public function setReady(bool $ready = false): self
    {
        $this->ready = $ready;
        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchase(): Collection
    {
        return $this->purchase;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchase->contains($purchase)) {
            $this->purchase->add($purchase);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        $this->purchase->removeElement($purchase);
        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
