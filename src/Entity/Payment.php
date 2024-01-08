<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use App\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Payment extends AbstractEntity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private PaymentType $paymentType;

    #[ORM\Column]
    private float $total;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private Purchase $purchase;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPaymentType(): PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
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
}
