<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\Platform;
use AppBundle\Service\Platform\PlatformDataServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;

class GameStateService implements GameStateServiceInterface
{
    const PLATFORM_UDATE_INTERVAL = 60; //seconds

    /**
     * @var AppServiceInterface
     */
    private $appService;

    /**
     * @var TimerServiceInterface
     */
    private $timerService;

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




    public function __construct(AppServiceInterface $appService,
                                TimerServiceInterface $timerService,
                                PlatformDataServiceInterface $platformDataService,
                                PlatformServiceInterface $platformService,
                                ProcessDueTasksServiceInterface $processDueTasksService)
    {
        $this->appService = $appService;
        $this->timerService = $timerService;
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
        $elapsed = $this->timerService->getElapsedTime($platform->getResourceUpdateTime());

        if($elapsed < self::PLATFORM_UDATE_INTERVAL) {
            //var_dump("Only $elapsed seconds have passed, still early!");
            return;
        }

        $platformService->updateTotalResources($elapsed, $platform, $this->appService);
        //var_dump('Resources updated');
    }
}