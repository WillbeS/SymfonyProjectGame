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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $unit = $this->unitRepository->find($id);
        $this->assertFound($unit);

        return $unit;
    }

    /**
     * @param Platform $platform
     * @return Unit[]
     */
    public function getAllByPlatform(Platform $platform): array
    {
        //TODO - decide
        //No need for this unles I plan to do a join or some special collection (key => value)
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
        if (!$platform) {
            return $this->unitRepository->findInTraining($platform);
        }

        return $platform->getUnits()->filter(function (Unit $unit) {
            return $unit->getStartBuild() !== null;
        });
    }

    public function createAllPlatformUnits(Platform $platform,
                                   BuildingServiceInterface $buildingService)
    {
        $types = $this->unitTypeService->findAll();

        foreach ($types as $type) {
            $unit = $this->generateUnit($type, $platform, $buildingService);
            $platform->addUnit($unit);
            $this->em->persist($unit);
        }
    }

    public function generateUnit(UnitType $unitType,
                                 Platform $platform,
                                 BuildingServiceInterface $buildingService): Unit
    {
        $building = $buildingService->getFromPlatformBuildingsByType($platform->getBuildings(), $unitType->getGameBuilding());
        $unit = new Unit();

        $unit
            ->setUnitType($unitType)
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


    public function startRecruiting($count,
                                    Unit $unit,
                                    PlatformServiceInterface $platformService)
    {
        if(null === $count || !is_numeric($count) || $count <= 0 || $count > PHP_INT_MAX) {
            return;
        }

        $platformService->payPrice($unit->getPlatform(), $unit->getPrice($count));

        $unit->addForTraining($count)
            ->setStartBuild(new \DateTime('now'));

        $this->em->flush();
    }

    private function assertFound($entity)
    {
        if(!$entity) {

            throw new NotFoundHttpException('Page Not Found');
        }
    }
}