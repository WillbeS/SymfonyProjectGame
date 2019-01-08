<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;

class GameStateService implements GameStateServiceInterface
{
    const PLATFORM_UDATE_INTERVAL = 60; //seconds
    const GAME_UPDATE_INTERVAL = 600; //todo decide later how much exactly

    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    /**
     * @var UnitServiceInterface
     */
    private $unitService;

    /**
     * @var AppServiceInterface
     */
    private $appService;

    /**
     * @var TimerServiceInterface
     */
    private $timerService;


    public function __construct(BuildingServiceInterface $buildingService,
                                UnitServiceInterface $unitService,
                                AppServiceInterface $appService,
                                TimerServiceInterface $timerService)
    {
        $this->buildingService = $buildingService;
        $this->unitService = $unitService;
        $this->appService = $appService;
        $this->timerService = $timerService;
    }

    public function updateBuildingsState(Platform $platform = null)
    {
        $buildings = $this->buildingService->getPending($platform);

        foreach ($buildings as $building) {
            if ($building->getRemainingTime($this->appService) < 0) {
                $this->buildingService->finishBuilding($building);
                $this->unitService->updateUnitStatus($platform);
            }
        }
    }

    public function updateUnitsInTrainingState(Platform $platform = null)
    {
        $unitsInTraining = $this->unitService->getWithUnitsInTraining($platform);

        foreach ($unitsInTraining as $unit) {
            /** @var Unit $unit $elapsed */
            $elapsed = $this->timerService->getElapsedTime($unit->getStartBuild());
            $this->unitService->updateUnitInTraining($elapsed, $unit);
        }
    }

    public function updatePlatformResourcesState(Platform $platform,
                                                 PlatformServiceInterface $platformService)
    {
        $elapsed = $this->timerService->getElapsedTime($platform->getResourceUpdateTime());

        if($elapsed < self::PLATFORM_UDATE_INTERVAL) {
            //var_dump("Only $elapsed seconds have passed, still early!");
            return;
        }

        $platformService->updateTotalResources($elapsed, $platform, $this->appService);
        //var_dump('Resources updated');
    }

    // TODO **For future commands use**
    public function updateResourceForAllPlatforms()
    {
        // Get the time of last scheduled update (by the interval const)
        // Get all platforms with res_update_date earlier than that time
        // Call updatePlatformResourcesState for all of them
    }
}