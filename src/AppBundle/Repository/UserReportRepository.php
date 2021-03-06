<?php

namespace AppBundle\Repository;

use AppBundle\Entity\UserReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReport::class);
    }

    public function findAllByUser(int $userId)
    {
        return $this->createQueryBuilder('ur')
            ->where('ur.user = :userId')
            ->join('ur.report', 'report')
            ->addSelect('report')
            ->setParameter('userId', $userId)
            ->orderBy('report.createdOn', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getNewReportsCount(int $userId)
    {
        return $this->createQueryBuilder('ur')
            ->select('count(ur.id)')
            ->where('ur.user = :userId')
            ->andWhere('ur.isRead = 0')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findOneByUserAndReport(int $userId, int $reportId)
    {
        return $this->createQueryBuilder('ur')
            ->join('ur.report', 'r')
            ->join('r.attacker', 'a')
            ->join('r.defender', 'd')
            ->join('r.winner', 'w')
            ->addSelect('r')
            ->addSelect('a')
            ->addSelect('d')
            ->addSelect('w')
            ->where('ur.user = :userId')
            ->andWhere('ur.report = :reportId')
            ->setParameter('userId', $userId)
            ->setParameter('reportId', $reportId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
