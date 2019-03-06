<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\Platform;
use AppBundle\Entity\UnitType;
use AppBundle\Repository\UnitTypeRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class UnitTypeService implements UnitTypeServiceInterface
{
    /**
     * @var UnitTypeRepository
     */
    private $unitTypeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    public function __construct(UnitTypeRepository $unitTypeRepository,
                                EntityManagerInterface $em,
                                BuildingServiceInterface $buildingService)
    {
        $this->unitTypeRepository = $unitTypeRepository;
        $this->em = $em;
        $this->buildingService = $buildingService;
    }


    public function findById(int $id): UnitType
    {
        return $this->unitTypeRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->unitTypeRepository->findAll();
    }

    public function isAvailable(UnitType $unitType,
                                      Platform $platform): bool
    {
        foreach ($unitType->getRequirements() as $requirement) {
            $building = $this->buildingService
                ->getByGameBuilding($requirement->getGameBuilding(), $platform);

            if($building->getLevel() < $requirement->getLevel()) {
                return false;
            }
        }

        return true;
    }
}