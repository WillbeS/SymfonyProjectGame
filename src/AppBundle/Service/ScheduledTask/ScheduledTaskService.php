<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;

class ScheduledTaskService implements ScheduledTaskServiceInterface
{
    /**
     * @var TimeCalculatorServiceInterface
     */
    private $timeCalculatorService;


    public function __construct(TimeCalculatorServiceInterface $timeCalculatorService)
    {
        $this->timeCalculatorService = $timeCalculatorService;
    }


    public function createPlatformUnitTask(int $taskType,
                               int $duration,
                               PlatformUnitInterface $platformUnit):ScheduledTask
    {

        $scheduledTask = new ScheduledTask();
        $this->setScheduledTask($duration, $taskType, $scheduledTask);

        $scheduledTask
            ->setOwnerId($platformUnit->getId())
            ->setPlatform($platformUnit->getPlatform())
        ;

        return $scheduledTask;
    }

    public function setScheduledTask(int $duration,
                                      int $taskType,
                                      ScheduledTaskInterface $scheduledTask)
    {
        $dueDate = $this->timeCalculatorService->getDueDate(
            new \DateTime('now'),
            $duration
        );

        $scheduledTask
            ->setStartDate(new \DateTime('now'))
            ->setDuration($duration)
            ->setDueDate($dueDate)
            ->setTaskType($taskType)
        ;

    }
}