<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;

interface PlatformServiceInterface
{
    public function getById(int $id): Platform;

    public function getByIdJoined(int $id): Platform;

    //todo - creates new platform, needs refactoring
    public function getNewPlatform(BuildingServiceInterface $buildingService,
                                   UnitServiceInterface $unitService,
                                   User $user = null): ?Platform;

    public function payPrice(Platform $platform, array $price);

//    public function payResources(Platform $platform,
//                                         $woodCost,
//                                         $foodCost,
//                                         $suppliesCost);

    public function updateTotalResources(int $elapsed,
                                         Platform $platform,
                                         AppServiceInterface $appService);
}