<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Entity\Unit;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;

interface UnitTrainingServiceInterface
{
    public function startTraining(int $count,
                                  Unit $unit,
                                  PlatformServiceInterface $platformService,
                                  ScheduledTaskServiceInterface $scheduledTaskService);

    public function finishTraining(ScheduledTaskInterface $trainingTask);
}