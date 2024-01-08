<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use App\Traits\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Purchase extends AbstractEntity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?float $total = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private User $userSystem;

    #[ORM\OneToMany(mappedBy: 'purchase', targetEntity: PurchaseItem::class, orphanRemoval: true)]
    private Collection $purchaseItems;

    #[ORM\Column(length: 255)]
    private string $buyerName;

    #[ORM\OneToMany(mappedBy: 'purchase', targetEntity: Payment::class, orphanRemoval: true)]
    private Collection $payments;

    #[ORM\ManyToMany(targetEntity: Status::class, mappedBy: 'purchase')]
    private Collection $statuses;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->statuses = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getShowTotal(): string
    {
        return 'R$ ' . number_format($this->getTotal(), 2, ',');
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getRemainingValue(): float
    {
        $total = $this->getTotal();

        /** @var Payment $payment */
        foreach($this->getPayments() as $payment){
            $total -= $payment->getTotal();
        }
        
        return $total;
    }

    public function setTotal(?float $total = null): self
    {
        $this->total = $total;
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

    /**
     * @return PersistentCollection<int, PurchaseItem>
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function hasPurchaseItems(): bool
    {
        return !$this->getPurchaseItems()->isEmpty();
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        $this->purchaseItems->add($purchaseItem);
        $purchaseItem->setPurchase($this);

        return $this;
    }

    public function getBuyerName(): string
    {
        return $this->buyerName;
    }

    public function setBuyerName(string $buyerName): self
    {
        $this->buyerName = $buyerName;
        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function hasPayment(): bool
    {
        return !$this->getPayments()->isEmpty();
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPurchase($this);
        }

        return $this;
    }

    public function getLastStatus(): ?Status
    {
        if(!$this->getStatuses()->isEmpty()){
            $criteria = new Criteria();
            $criteria->orderBy(['id' => 'DESC']);
            $criteria->setMaxResults(1);

            return $this->getStatuses()->matching($criteria)->first();
        }

        return null;
    }

    /**
     * @return PersistentCollection<int, Status>
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function hasStatus(Status $status): bool
    {
        return $this->getStatuses()->contains($status);
    }

    public function addStatus(Status $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses->add($status);
            $status->addPurchase($this);
        }

        return $this;
    }

    public function removeStatus(Status $status): self
    {
        if ($this->statuses->removeElement($status)) {
            $status->removePurchase($this);
        }

        return $this;
    }
}
