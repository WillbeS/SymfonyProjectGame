<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\Utils\PriceCalculatorServiceInterface;

interface BuildingUpgradeServiceInterface
{
    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 ScheduledTaskServiceInterface $taskService);

    // TODO:
    // finish upgrade
    // get nextLevel price, etc
}