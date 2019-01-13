<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Platform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PlatformRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Platform::class);
    }

    public function findOneWithBuildings(int $id)
    {
        return $this->createQueryBuilder('p')
            ->join('p.buildings', 'b')
            ->join('b.gameBuilding', 'gb')
            ->join('p.units', 'u')
            ->join('u.unitType', 'ut')
            ->addSelect('b')
            ->addSelect('gb')
            ->addSelect('u')
            ->addSelect('ut')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
