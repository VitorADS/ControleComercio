<?php

namespace App\Service;

use App\Entity\AbstractEntity;
use App\Entity\Purchase;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PurchaseService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, Purchase::class);
    }

    /** 
     * @param Purchase $entity
     * @param ?int $id = null
     * @return AbstractEntity
     * @throws Exception
     */
    public function save(AbstractEntity $entity, ?int $id = null): AbstractEntity
    {
        try{
            if($id === null){
                $status = (new StatusService($this->getEntityManager()))->getRepository()->findOneBy(['new' => true]);
                $entity->addStatus($status);
            }
    
            if($entity->getLastStatus()->isFinished() && $entity->hasPurchaseItems()){
                throw new Exception('Nao possui nenhum item, impossivel finalizar!');
            }
    
            return parent::save($entity, $id);
        }catch(Exception $e){
            throw $e;
        }
    }

    /** 
     * @param Purchase $entity
     * @return bool
     * @throws Exception
     */
    public function remove(AbstractEntity $entity): bool
    {
        try{
            if($entity->hasPayment()){
                throw new Exception('Venda possui pagamentos!');
            }

            return parent::remove($entity);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function finishPurchase(Purchase $purchase): Purchase
    {
        /** @var Status $status */
        $status = (new StatusService($this->getEntityManager()))->findOneBy(['finished' => true]);

        if($purchase->hasStatus($status)){
            throw new Exception('Venda ja finalizada!');
        } 

        if(!$purchase->hasPurchaseItems()){
            throw new Exception('Nenhum item na venda!');
        }

        $purchase->addStatus($status);
        return $this->save($purchase, $purchase->getId());
    }
}