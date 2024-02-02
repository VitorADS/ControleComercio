<?php

namespace App\Entity;

use App\Repository\PurchaseItemRepository;
use App\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PurchaseItem extends AbstractEntity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Purchase $purchase;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private int $quantity;

    #[ORM\Column]
    private float $subTotal;

    #[ORM\ManyToOne(inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private User $userSystem;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(Purchase $purchase): self
    {
        $this->purchase = $purchase;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes = null): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getSubTotal(): float
    {
        return $this->subTotal;
    }

    public function getShowSubTotal(): string
    {
        return number_format($this->getSubTotal(), 2, ',');
    }

    public function setSubTotal(): self
    {
        $this->subTotal = $this->getQuantity() * $this->getProduct()->getValue();
        return $this;
    }

    public function getUserSystem(): User
    {
        return $this->userSystem;
    }

    public function setUserSystem(User $userSystem): self
    {
        $this->userSystem = $userSystem;
        return $this;
    }
}
