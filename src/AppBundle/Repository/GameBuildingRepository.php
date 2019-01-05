<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Building\BuildingType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;

/**
 * BuildingTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuildingTypeRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(BuildingType::class));
    }
}