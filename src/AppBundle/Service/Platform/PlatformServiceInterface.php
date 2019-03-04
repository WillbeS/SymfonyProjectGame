<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Entity\User;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;

interface PlatformServiceInterface
{
    public function getById(int $id): Platform;

    public function getOneJoinedAll(int $id): Platform;

    public function getPlatfomUnit(int $unitId, Platform $platform): Unit;

    public function getPlatfomBuilding(int $buildingId, Platform $platform): Building;

    public function getOneJoinedWithUnitsResources(int $id): Platform;

    public function getNewPlatform(BuildingServiceInterface $buildingService,
                                   UnitServiceInterface $unitService,
                                   User $user = null): ?Platform;

    public function payPrice(Platform $platform, array $price);


    public function updateTotalResources(int $elapsed,
                                         Platform $platform);
}