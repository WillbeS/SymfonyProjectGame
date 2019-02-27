<?php

namespace AppBundle\Repository;
use AppBundle\Entity\BattleReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * BattleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BattleReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BattleReport::class);
    }

//    public function findAllByUser(int $userId)
//    {
//        return $this->createQueryBuilder('br')
//            ->where('br.attacker = :userId OR br.defender = :userId')
//            ->setParameter('userId', $userId)
//            ->orderBy('br.createdOn', 'DESC')
//            ->getQuery()
//            ->getResult();
//    }
//
//    public function getNewReportsCount(int $userId)
//    {
//        return $this->createQueryBuilder('br')
//            ->select('count(br.id)')
//            ->where('br.attacker = :userId OR br.defender = :userId')
//            ->andWhere('br.isRead = 0')
//            ->setParameter('userId', $userId)
//            ->getQuery()
//            ->getSingleScalarResult();
//    }
}