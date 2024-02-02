<?php

namespace App\Service;

use App\Entity\AbstractEntity;
use App\Entity\Purchase;
use App\Entity\Status;
use App\Utils\FormatNumber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    
            $this->isValid($entity);
    
            return parent::save($entity, $id);
        }catch(Exception $e){
            throw $e;
        }
    }

    private function isValid(Purchase $entity): void
    {
        if($entity->isFinished() && !$entity->hasPurchaseItems()){
            throw new Exception('Nao possui nenhum item, impossivel finalizar!');
        }

        if($entity->isFinished() && $entity->getRemainingValue() > 0){
            throw new Exception('Nao e possivel finalizar, necessario pagar R$ ' . FormatNumber::format($entity->getRemainingValue()));
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
            if($entity->isFinished()){
                throw new Exception('Venda finalizada!');
            }

            if($entity->hasPayment()){
                throw new Exception('Venda possui pagamentos!');
            }

            return parent::remove($entity);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function addStatus(Purchase $purchase, Status $status): Purchase
    {
        if($purchase->hasStatus($status)){
            throw new Exception('Status ja adicionado');
        }

        $purchase->addStatus($status);
        return $this->save($purchase, $purchase->getId());
    }
}