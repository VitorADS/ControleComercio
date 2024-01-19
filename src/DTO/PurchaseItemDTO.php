<?php

namespace App\DTO;

use App\Entity\Product;
use App\Entity\User;

class PurchaseItemDTO
{
    public function __construct(
        public ?Product $product = null,
        public ?int $quantity = null,
        public ?int $purchase = null,
        public ?float $subTotal = null,
        public ?User $userSystem = null
    )
    {
    }
}