<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Repository\MilitaryCampaignRepository;
use AppBundle\Repository\ScheduledTaskRepository;
use AppBundle\Service\ArmyMovement\JourneyServiceInterface;
use AppBundle\Service\ArmyMovement\MilitaryCampaignServiceInterface;
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
     * @var MilitaryCampaignServiceInterface
     */
    private $militaryCampaignService;

    /**
     * @var MilitaryCampaignRepository
     */
    private $militaryCampaignRepository;

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
                                UnitTrainingServiceInterface $unitTrainingService,
                                MilitaryCampaignServiceInterface $militaryCampaignService,
                                MilitaryCampaignRepository $militaryCampaignRepository)
    {
        $this->em = $em;
        $this->scheduleTaskRepository = $scheduledTaskRepository;

        $this->battleService = $battleService;
        $this->journeyService = $journeyService;
        $this->buildingUpgradeService = $buildingUpgradeService;
        $this->unitService = $unitService;
        $this->unitTrainingService = $unitTrainingService;
        $this->militaryCampaignService = $militaryCampaignService;
        $this->militaryCampaignRepository = $militaryCampaignRepository;
    }

    //TODO - really try and use events if there's time!!!
    public function processDueTasks(array $dueTasks)
    {
        if (0 === count($dueTasks)) {
            return;
        }

        foreach ($dueTasks as $dueTask) {
            /**
             * @var ScheduledTaskInterface $dueTask
             */
            switch ($dueTask->getTaskType()) {
                case ScheduledTask::BUILDING_UPGRADE:
                    $this->buildingUpgradeService->finishUpgrade($dueTask);
                    $this->unitService->updateUnitStatus($dueTask->getPlatform());
                    break;
                case ScheduledTask::UNIT_TRAINING:
                    $this->unitTrainingService->finishTraining($dueTask);
                    break;
                case ScheduledTask::ATTACK_JOURNEY:
                    /** @var MilitaryCampaign $dueTask */
                    $this->militaryCampaignService->processCampaign($dueTask);
                    break;
                case ScheduledTask::RETURN_JOURNEY:
                    /** @var MilitaryCampaign $dueTask */
                    $this->militaryCampaignService->processReturnCampaign($dueTask);
                    break;
            }
        }

        //todo - decide if put the flush here for all or keep it inside
    }

    public function processDueTasksByPlatform(int $platformId)
    {
        $tasks = $this->scheduleTaskRepository->findDueTasksByPlatform($platformId);
        $this->processDueTasks($tasks);
    }

    public function processDueCampaignTasksByPlatform(int $platformId)
    {
        $tasks = $this->militaryCampaignRepository->findDueCampaignsByPlatform($platformId);
        $this->processDueTasks($tasks);
    }
}