<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;

interface ScheduledTaskServiceInterface
{
    public function createPlatformUnitTask(int $taskType,
                                           int $duration,
                                           PlatformUnitInterface $platformUnit):ScheduledTask;

    public function setScheduledTask(int $duration,
                                     int $taskType,
                                     ScheduledTaskInterface $scheduledTask);
}