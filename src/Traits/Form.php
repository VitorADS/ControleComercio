<?php

namespace App\Traits;

use App\DTO\PaymentDTO;
use App\DTO\PurchaseItemDTO;
use App\Entity\Purchase;
use App\Form\AddPaymentType;
use App\Form\PurchaseItemType;
use Symfony\Component\Form\FormInterface;

trait Form
{
    private function createPaymentForm(PaymentDTO &$payment): FormInterface
    {
        $paymentForm = $this->createForm(
            AddPaymentType::class,
            $payment,
            [
                'action' => $this->generateUrl('app_purchase_add_payment', ['purchase' => $payment->purchase->getId()]),
                'method' => 'POST'
            ]
        );

        return $paymentForm;
    }

    private function createPurchaseForm(PurchaseItemDTO &$purchaseItemDTO, Purchase $purchase): FormInterface
    {
        $purchaseItemDTO->purchase = $purchase->getId();
        $purchaseItemForm = $this->createForm(
            PurchaseItemType::class,
            $purchaseItemDTO,
            [
                'action' => $this->generateUrl('app_purchase_add_item'),
                'method' => 'POST'
            ]
        );

        return $purchaseItemForm;
    }
}