<?php

namespace App\DTO;

use App\Entity\PaymentType;
use App\Entity\Purchase;
use App\Entity\User;

class PaymentDTO
{
    public function __construct(
        public ?PaymentType $paymentType = null,
        public ?User $userSystem = null,
        public ?Purchase $purchase = null,
        public ?string $description = null,
        public ?float $total = null,
    )
    {
    }
}