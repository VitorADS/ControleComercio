<?php

namespace App\Service;

use App\Entity\AbstractEntity;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class StatusService extends AbstractService
{
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($entityManager, Status::class);
    }

    /**
     * @param Status $entity,
     * @param ?int $id = null
     * @return AbstractEntity
     * @throws Exception
     */
    public function save(AbstractEntity $entity, ?int $id = null): AbstractEntity
    {
        if($entity->isNew()){
            $status = $this->getRepository()->findOneBy(['new' => true]);

            if($status instanceof Status){
                throw new Exception('Ja existe um status novo cadastrado');
            }
        }

        if($entity->isFinished()){
            $status = $this->getRepository()->findOneBy(['finished' => true]);

            if($status instanceof Status){
                throw new Exception('Ja existe um status finalizado cadastrado');
            }
        }

        return parent::save($entity, $id);
    }
}