<?php

namespace AppBundle\Repository;

use AppBundle\Entity\UserTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTopic::class);
    }

    public function findOneByUserAndTopic(int $userId, int $topicId)
    {
        return $this->createQueryBuilder('ut')
            ->join('ut.topic', 't')
            ->addSelect('t')
            ->where('ut.user = :userId')
            ->andWhere('ut.topic = :topicId')
            ->setParameter('userId', $userId)
            ->setParameter('topicId', $topicId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByUser(int $userId)
    {
        return $this->createQueryBuilder('ut')
            ->join('ut.topic', 't')
            ->addSelect('t')
            ->where('ut.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('t.updatedOn', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getNewTopicsCount(int $userId)
    {
        return $this->createQueryBuilder('ut')
            ->select('count(ut.id)')
            ->where('ut.user = :userId')
            ->andWhere('ut.isRead = 0')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
