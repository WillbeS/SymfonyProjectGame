<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;

interface BuildingUpgradeServiceInterface
{
    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 ScheduledTaskServiceInterface $taskService);

    public function finishUpgrade(ScheduledTaskInterface $upgradeTask);
}