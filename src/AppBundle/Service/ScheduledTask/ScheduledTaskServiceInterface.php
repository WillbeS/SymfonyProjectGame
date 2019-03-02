<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;

interface ScheduledTaskServiceInterface
{
    public function createPlatformUnitTask(int $taskType,
                                           int $duration,
                                           PlatformUnitInterface $platformUnit):ScheduledTask;


    //old - for delete
    public function createJourneyTask(int $taskType,
                                      int $duration,
                                      Platform $platform):ScheduledTask;
}