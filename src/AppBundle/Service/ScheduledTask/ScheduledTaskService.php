<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Service\Utils\CountdownServiceInterface;

class ScheduledTaskService implements ScheduledTaskServiceInterface
{
    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * ScheduledTaskService constructor.
     * @param CountdownServiceInterface $countdownService
     */
    public function __construct(CountdownServiceInterface $countdownService)
    {
        $this->countdownService = $countdownService;
    }


    public function createTask(int $taskType,
                               int $duration,
                               PlatformUnitInterface $platformUnit):ScheduledTask
    {
        $dueDate = $this->countdownService->getEndDate(
            new \DateTime('now'),
            $duration
        );

        $scheduledTask = (new ScheduledTask())
            ->setTaskType($taskType)
            ->setOwnerId($platformUnit->getId())
            ->setPlatform($platformUnit->getPlatform())
            ->setDuration($duration)
            ->setDueDate($dueDate)
        ;

        return $scheduledTask;
    }
}