<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Repository\MilitaryCampaignRepository;
use AppBundle\Repository\ScheduledTaskRepository;
use AppBundle\Service\ArmyMovement\MilitaryCampaignServiceInterface;
use AppBundle\Service\Building\BuildingUpgradeServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTrainingServiceInterface;


class ProcessDueTasksService implements ProcessDueTasksServiceInterface
{
    /**
     * @var ScheduledTaskRepository
     */
    private $scheduleTaskRepository;

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


    public function __construct(ScheduledTaskRepository $scheduledTaskRepository,
                                BuildingUpgradeServiceInterface $buildingUpgradeService,
                                UnitServiceInterface $unitService,
                                UnitTrainingServiceInterface $unitTrainingService,
                                MilitaryCampaignServiceInterface $militaryCampaignService,
                                MilitaryCampaignRepository $militaryCampaignRepository)
    {
        $this->scheduleTaskRepository = $scheduledTaskRepository;
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