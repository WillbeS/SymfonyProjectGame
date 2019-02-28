<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;

interface ScheduledTaskServiceInterface
{
    public function createTask(int $taskType,
                               int $duration,
                               PlatformUnitInterface $platformUnit):ScheduledTask;
}