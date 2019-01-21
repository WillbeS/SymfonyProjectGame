<?php

namespace AppBundle\Service\Unit;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitType;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;

interface UnitServiceInterface
{
    public function getById(int $id): Unit;

    public function getAllByPlatform(Platform $platform): array;

    public function getWithUnitsInTraining(Platform $platform = null);

    public function updateUnitStatus(Platform $platform); // flushes

    public function updateUnitInTraining(int $elapsed, Unit $unit); //flushes

    /**
     * @param Building $building
     * @return Unit[]
     */
    public function getAllByBuilding(Building $building): array;

    public function startRecruiting($count,
                                    Unit $unit,
                                    PlatformServiceInterface $platformService); //flushes

    public function generateUnit(UnitType $unitType,
                               Platform $platform,
                               BuildingServiceInterface $buildingService): Unit;

    /**
     * @param Platform $platform
     * @return Unit[]
     */
    public function createAllPlatformUnits(Platform $platform,
                                   BuildingServiceInterface $buildingService);


}