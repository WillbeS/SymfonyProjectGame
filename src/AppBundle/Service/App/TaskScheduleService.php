<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Repository\ScheduledTaskRepository;
use AppBundle\Service\ArmyMovement\JourneyServiceInterface;
use AppBundle\Service\Battle\BattleServiceInterface;
use AppBundle\Service\Building\BuildingUpgradeServiceInterface;
use AppBundle\Service\Utils\CountdownServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

//TODO - rename and move in ScheduledTask dir
class TaskScheduleService implements TaskScheduleServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ScheduledTaskRepository
     */
    private $scheduleTaskRepository;

    /**
     * @var BattleServiceInterface
     */
    private $battleService;

    /**
     * @var JourneyServiceInterface
     */
    private $journeyService;

    /**
     * @var BuildingUpgradeServiceInterface
     */
    private $buildingUpgradeService;

    /**
     * TaskScheduleService constructor.
     * @param CountdownServiceInterface $countdownService
     */
    public function __construct(ScheduledTaskRepository $scheduledTaskRepository,
                                EntityManagerInterface $em,
                                BattleServiceInterface $battleService,
                                JourneyServiceInterface $journeyService,
                                BuildingUpgradeServiceInterface $buildingUpgradeService)
    {
        $this->em = $em;
        $this->scheduleTaskRepository = $scheduledTaskRepository;

        $this->battleService = $battleService;
        $this->journeyService = $journeyService;
        $this->buildingUpgradeService = $buildingUpgradeService;
    }

    public function processDueTasksByPlatform(int $platformId)
    {
        $dueTasks = $this->scheduleTaskRepository->findDueTasksByPlatform($platformId);

        if (0 === count($dueTasks)) {
            return;
        }

        foreach ($dueTasks as $dueTask) {
            /**
             * @var ScheduledTask $dueTask
             */
            switch ($dueTask->getTaskType()) {
                case ScheduledTask::BUILDING_UPGRADE:
                    $this->buildingUpgradeService->finishUpgrade($dueTask);
                    break;
            }
        }
    }


    // Old code

    public function processDueTasks(string $entityType): bool
    {
        $dueTasks = $this->getDueTasks($entityType);

        if (0 == count($dueTasks)) {
            return true;
        }

        //TODO - use events if decide to refactor other entities
        switch ($entityType) {
            case ArmyJourney::class:
                $this->journeyService->processBattleJourneys($dueTasks);
                break;
        }

        return true;
    }


    private function getDueTasks(string $entityType):array
    {
        $qb = $this->em->createQueryBuilder();
        return $qb->select('t')
            ->from($entityType, 't')
            ->where('t.dueDate <= :now')
            ->setParameter('now', new \DateTime('now'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();
    }
}