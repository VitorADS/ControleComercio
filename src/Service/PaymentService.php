<?php

namespace App\Service;

use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;

class PaymentService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, Payment::class);
    }
}