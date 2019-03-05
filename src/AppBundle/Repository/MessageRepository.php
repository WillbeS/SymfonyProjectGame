<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findAllTopicMessages(int $topicId, $limit = null)
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.sender', 's')
            ->addSelect('s')
            ->where('m.topic = :topicId')
            ->setParameter('topicId', $topicId)
            ->orderBy('m.createdOn', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }
}
