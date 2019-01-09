<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitType;
use AppBundle\Repository\UnitRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class UnitService implements UnitServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UnitRepository
     */
    private $unitRepository;

    /**
     * @var UnitTypeServiceInterface
     */
    private $unitTypeService;


    public function __construct(EntityManagerInterface $em,
                                UnitRepository $unitRepository,
                                UnitTypeServiceInterface $unitTypeService)
    {
        $this->em = $em;
        $this->unitRepository = $unitRepository;
        $this->unitTypeService = $unitTypeService;
    }

    public function getById(int $id): Unit
    {
        return $this->unitRepository->find($id);
    }

    /**
     * @param Platform $platform
     * @return Unit[]
     */
    public function getAllByPlatform(Platform $platform): array
    {
        return $this->unitRepository->findBy(['platform' => $platform]);
    }

    /**
     * @param Building $building
     * @return Unit[]
     */
    public function getAllByBuilding(Building $building): array
    {
        return $this->unitRepository->findBy(['building' => $building]);
    }

    public function getWithUnitsInTraining(Platform $platform = null)
    {
        return $this->unitRepository->findInTraining($platform);
    }

    public function createAllTypes(Platform $platform,
                                       BuildingServiceInterface $buildingService)
    {
        $types = $this->unitTypeService->findAll();

        foreach ($types as $type) {
            $unit = $this->generateUnit($type, $platform, $buildingService);
            $this->em->persist($unit);
        }

        $this->em->flush();
    }

    public function generateUnit(UnitType $unitType,
                               Platform $platform,
                               BuildingServiceInterface $buildingService): Unit
    {
        $building = $buildingService->getByGameBuilding($unitType->getGameBuilding(), $platform);

        $unit = new Unit();

        $unit
            ->setUnitType($unitType)
            ->setPlatform($platform)
            ->setBuilding($building)
            ->addForTraining(0)
            ->setIddle(0)
            ->setInBattle(0)
            ->setIsAvailable(false);

        return $unit;
    }


    public function updateUnitStatus(Platform $platform)
    {
        $units = $this->getAllByPlatform($platform);

        foreach ($units as $unit) {
            $isAvailable = $this->unitTypeService->isAvailable($unit->getUnitType(), $platform);

            if($unit->isAvailable() !== $isAvailable) {
                var_dump('Will flush');
                $unit->setIsAvailable($isAvailable);
                $this->em->flush(); //TODO -  check how it works outside the loop
            }
        }
    }

    public function updateUnitInTraining(int $elapsed, Unit $unit)
    {
        $ready = floor($elapsed/$unit->getUnitType()->getBuildTime());

        if ($ready == 0) {
            return;
        }

        $ready = $ready <= $unit->getInTraining() ? $ready : $unit->getInTraining();
        $unit->addForTraining($ready * -1);

        $unit->setIddle($unit->getIddle() + $ready);

        if ($unit->getInTraining() > 0) {
            $unit->setStartBuild(new \DateTime('now'));
        } else {
            $unit->setStartBuild(null);
        }

        $this->em->flush();
    }


    public function startRecruiting(Unit $unit,
                                    Platform $platform,
                                    PlatformServiceInterface $platformService)
    {
        $count = $unit->getForTraining();

        if(null == $count || $count < 0) {
            return;
        }

        $woodCost = $unit->getUnitType()->getWoodCost() * $count;
        $foodCost = $unit->getUnitType()->getFoodCost() * $count;
        $suppliesCost = $unit->getUnitType()->getSuppliesCost() * $count;

        $platformService->payResources($platform, $woodCost, $foodCost, $suppliesCost);
        $unit->addForTraining($count)
            ->setStartBuild(new \DateTime('now'));

        $this->em->flush();
    }
}