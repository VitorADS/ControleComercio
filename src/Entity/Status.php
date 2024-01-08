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
    private bool $new;

    #[ORM\Column]
    private bool $finished;

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

    public function setNew(bool $new): self
    {
        $this->new = $new;
        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;
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
