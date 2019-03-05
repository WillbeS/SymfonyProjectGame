<?php

namespace AppBundle\Repository;

use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\ScheduledTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class MilitaryCampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MilitaryCampaign::class);
    }

    public function findDueCampaignsByPlatform(int $platformId):array
    {
        return $this->createQueryBuilder('mc')
            ->where('mc.origin = :platformId or mc.destination = :platformId')
            ->andWhere('mc.dueDate <= :now')
            ->setParameter('platformId', $platformId)
            ->setParameter('now', new \DateTime('now'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();
    }

    public function getEnemyAttacksCount(int $platformId)
    {
        return $this->createQueryBuilder('mc')
            ->select('count(mc.id)')
            ->where('mc.destination = :platformId')
            ->andWhere('mc.taskType = ' . ScheduledTask::ATTACK_JOURNEY)
            ->setParameter('platformId', $platformId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
