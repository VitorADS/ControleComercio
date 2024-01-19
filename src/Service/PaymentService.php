<?php

namespace App\Service;

use App\DTO\PaymentDTO;
use App\Entity\AbstractEntity;
use App\Entity\Payment;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PaymentService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, Payment::class);
    }

    public function addPayment(PaymentDTO $paymentDTO): void
    {
        $purchase = $paymentDTO->purchase;

        if($purchase->getLastStatus()->isFinished()){
            throw new Exception('Venda finalizada!');
        }

        if($paymentDTO->total > $purchase->getRemainingValue()){
            throw new Exception('Valor do pagamento maior que o valor restante!');
        }

        if(!$purchase->hasPurchaseItems()){
            throw new Exception('Venda sem itens!');
        }

        $payment = new Payment();
        $payment->setDescription($paymentDTO->description);
        $payment->setPurchase($purchase);
        $payment->setTotal($paymentDTO->total);
        $payment->setPaymentType($paymentDTO->paymentType);

        $this->save($payment);
    }

    /**
     * @param Payment $entity
     * @return bool
     */
    public function remove(AbstractEntity $entity): bool
    {
        if($entity->getPurchase()->getLastStatus() instanceof Status && $entity->getPurchase()->getLastStatus()->isFinished()){
            throw new Exception('Venda ja finalizada!');
        }

        return parent::remove($entity);
    }
}