<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;

interface PlatformServiceInterface
{
    public function getById(int $id): Platform;

    //todo - creates new platform, needs refactoring
    public function getNewPlatform(BuildingServiceInterface $buildingService,
                                   User $user = null): ?Platform;

    public function payResources(Platform $platform,
                                         $woodCost,
                                         $foodCost,
                                         $suppliesCost);

    public function updateTotalResources(int $elapsed,
                                         Platform $platform,
                                         AppServiceInterface $appService);
}