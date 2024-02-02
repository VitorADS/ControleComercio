<?php

namespace App\Service;

use App\DTO\PurchaseItemDTO;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PurchaseItemService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, PurchaseItem::class);
    }

    public function addItem(PurchaseItemDTO $dto): void
    {
        if($dto->quantity < 1){
            throw new Exception('Quantidade precisa ser maior que 0');
        }

        /** @var Purchase $purchase */
        $purchase = (new PurchaseService($this->getEntityManager()))->findOneBy(['id' => $dto->purchase]);

        if($purchase->isFinished()){
            throw new Exception('Compra finalizada!');
        }

        $purchaseItem = new PurchaseItem();
        $purchaseItem->setPurchase($purchase);
        $purchaseItem->setNotes($dto->notes);
        $purchaseItem->setQuantity($dto->quantity);
        $purchaseItem->setProduct($dto->product);
        $purchaseItem->setSubTotal();
        $purchaseItem->setUserSystem($dto->userSystem);
        $purchase->setTotal($purchase->getTotal() + $purchaseItem->getSubTotal());

        $this->save($purchaseItem);
    }

    public function removeItem(PurchaseItem $purchaseItem): void
    {
        if($purchaseItem->getPurchase()->isFinished()){
            throw new Exception('Compra finalizada!');
        }

        if($purchaseItem->getPurchase()->hasPayment()){
            throw new Exception('Compra possui pagamentos!');
        }

        $purchase = $purchaseItem->getPurchase();
        $purchase->setTotal($purchase->getTotal() - $purchaseItem->getSubTotal());
        $this->remove($purchaseItem);
    }
}