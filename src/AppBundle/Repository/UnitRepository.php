<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UnitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    public function findInTraining(Platform $platform = null)
    {
        $qb = $this->createQueryBuilder('unit');
        $qb->where('unit.startBuild is not null');
        if ($platform !== null) {
            $qb->andWhere('unit.platform = :platform')
                ->setParameter('platform', $platform);
        }

        return $qb->getQuery()->getResult();
    }
}
