<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BuildingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Building::class);
    }

    public function findPending(Platform $platform = null)
    {
        $qb = $this->createQueryBuilder('building');
        $qb->where('building.startBuild is not null');
        if ($platform !== null) {
            $qb->andWhere('building.platform = :platform')
                ->setParameter('platform', $platform);
        }

        return $qb->getQuery()->getResult();
    }
}
