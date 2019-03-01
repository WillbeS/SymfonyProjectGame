<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Repository\ScheduledTaskRepository;
use AppBundle\Service\ArmyMovement\JourneyServiceInterface;
use AppBundle\Service\Battle\BattleServiceInterface;
use AppBundle\Service\Building\BuildingUpgradeServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTrainingServiceInterface;
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
     * @var UnitServiceInterface
     */
    private $unitService;

    /**
     * @var UnitTrainingServiceInterface
     */
    private $unitTrainingService;

    /**
     * TaskScheduleService constructor.
     * @param CountdownServiceInterface $countdownService
     */
    public function __construct(ScheduledTaskRepository $scheduledTaskRepository,
                                EntityManagerInterface $em,
                                BattleServiceInterface $battleService,
                                JourneyServiceInterface $journeyService,
                                BuildingUpgradeServiceInterface $buildingUpgradeService,
                                UnitServiceInterface $unitService,
                                UnitTrainingServiceInterface $unitTrainingService)
    {
        $this->em = $em;
        $this->scheduleTaskRepository = $scheduledTaskRepository;

        $this->battleService = $battleService;
        $this->journeyService = $journeyService;
        $this->buildingUpgradeService = $buildingUpgradeService;
        $this->unitService = $unitService;
        $this->unitTrainingService = $unitTrainingService;
    }

    //TODO - really try and use events if there's time!!!
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
                    $this->unitService->updateUnitStatus($dueTask->getPlatform());
                    break;
                case ScheduledTask::UNIT_TRAINING:
                    $this->unitTrainingService->finishTraining($dueTask);
                    break;
            }
        }

        //todo - decide if put the flush here for all or keep it inside
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