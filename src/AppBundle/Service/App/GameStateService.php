<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\Platform;
use AppBundle\Service\Platform\PlatformDataServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\TimeCalculatorServiceInterface;

class GameStateService implements GameStateServiceInterface
{
    const PLATFORM_UDATE_INTERVAL = 60; //seconds

    /**
     * @var TimeCalculatorServiceInterface
     */
    private $timeCalculatorService;

    /**
     * @var PlatformDataServiceInterface
     */
    private $platformService;

    /**
     * @var PlatformDataServiceInterface
     */
    private $platformDataService;

    /**
     * @var ProcessDueTasksServiceInterface
     */
    private $processTasksService;




    public function __construct(TimeCalculatorServiceInterface $timeCalculatorService,
                                PlatformDataServiceInterface $platformDataService,
                                PlatformServiceInterface $platformService,
                                ProcessDueTasksServiceInterface $processDueTasksService)
    {
        $this->timeCalculatorService = $timeCalculatorService;
        $this->platformDataService = $platformDataService;
        $this->platformService = $platformService;
        $this->processTasksService = $processDueTasksService;
    }

    public function updatePlatformState(): bool
    {
        $platform = $this->platformDataService->getCurrentPlatform();
        $this->updatePlatformResourcesState($platform, $this->platformService);
        $this->processTasksService->processDueTasksByPlatform($platform->getId());
        $this->processTasksService->processDueCampaignTasksByPlatform($platform->getId());

        return true;
    }

    public function updatePlatformResourcesState(Platform $platform,
                                                 PlatformServiceInterface $platformService)
    {
        $elapsed = $this->timeCalculatorService->getElapsedTime($platform->getResourceUpdateTime());

        if($elapsed < self::PLATFORM_UDATE_INTERVAL) {
            return;
        }

        $platformService->updateTotalResources($elapsed, $platform);
    }
}