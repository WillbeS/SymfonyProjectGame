<?php

namespace AppBundle\Service\ScheduledTask;

use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
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


    public function createPlatformUnitTask(int $taskType,
                               int $duration,
                               PlatformUnitInterface $platformUnit):ScheduledTask
    {
        $scheduledTask = $this->createScheduledTask(
            $taskType,
            $duration,
            $platformUnit->getPlatform()
        );

        $scheduledTask->setOwnerId($platformUnit->getId());

        return $scheduledTask;
    }

    //todo - delete
    public function createJourneyTask(int $taskType,
                                      int $duration,
                                      Platform $platform): ScheduledTask
    {
        $scheduledTask = $this->createScheduledTask(
            $taskType,
            $duration,
            $platform
        );

        $scheduledTask->setOwnerId($platform->getId());

        return $scheduledTask;
    }

    private function createScheduledTask(int $taskType,
                                         int $duration,
                                         Platform $platform): ScheduledTask
    {
        $dueDate = $this->countdownService->getEndDate(
            new \DateTime('now'),
            $duration
        );

        $scheduledTask = (new ScheduledTask())
            ->setTaskType($taskType)
            ->setPlatform($platform)
            ->setDuration($duration)
            ->setStartDate(new \DateTime('now'))
            ->setDueDate($dueDate)
        ;

        return $scheduledTask;
    }
}