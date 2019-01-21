<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllWithPlatform()
    {
        return $this->createQueryBuilder('u')
            ->join('u.currentPlatform', 'p')
            ->addSelect('p')
            ->getQuery()
            ->getResult();
    }

    public function findOneWithPlatform(int $id)
    {
        return $this->createQueryBuilder('u')
            ->join('u.currentPlatform', 'p')
            ->addSelect('p')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
