<?php

namespace App\Service;

use App\Entity\PaymentType;
use Doctrine\ORM\EntityManagerInterface;

class PaymentTypeService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, PaymentType::class);
    }
}