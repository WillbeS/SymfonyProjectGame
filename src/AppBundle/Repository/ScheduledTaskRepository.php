<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ScheduledTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ScheduledTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduledTask::class);
    }

    public function findDueTasksByPlatform(int $platformId):array
    {
        return $this->createQueryBuilder('st')
            ->where('st.platform = :platformId')
            ->andWhere('st.dueDate <= :now')
            ->setParameter('platformId', $platformId)
            ->setParameter('now', new \DateTime('now'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();
    }
}
