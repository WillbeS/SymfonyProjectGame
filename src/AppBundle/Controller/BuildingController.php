<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\ViewData\BuildingData;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends MainController
{
    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    public function __construct(PlatformServiceInterface $platformService,
                                GameStateServiceInterface $gameStateService,
                                BuildingServiceInterface $buildingService,
                                AppServiceInterface $appService)
    {
        parent::__construct($appService, $gameStateService, $platformService); //TODO clean up when safe
        $this->buildingService = $buildingService;
    }


    /**
     * @Route("/settlement/{platformId}/building/view/{buildingId}",
     *     name="show_building",
     *     requirements={"platformId" = "\d+"}
     *     )
     *
     * @param PlatformServiceInterface $platformService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(int $platformId,
                               int $buildingId)
    {

        /** @var Building $building */
        $building = $this->buildingService->findById($buildingId);
        $platform = $this->getPlatform($platformId);

        return $this->render('building/show.html.twig', [
            'platform' => $platform,
            'building' => $building,
            'appService' => $this->appService,
        ]);
    }

    /**
     * @Route("settlement/{id}/buildings/manage/", name="manage_buildings", requirements={"id" = "\d+"})
     */
    public function manageAllAction($id, TimerServiceInterface $timerService)
    {
        $platform = $this->getPlatform($id);
        /**
         * @var Building[] $buildings
         */
        $buildings = $platform->getBuildings();

        return $this->render('building/manage-all.html.twig', [
            'platform' => $platform,
            'buildings' => $buildings,
            'appService' => $this->appService,
        ]);
    }

    /**
     * @Route("/settlement/{platformId}/building/upgrade/{buildingId}",
     *     name="start_upgrade",
     *     requirements={"platformId" = "\d+"}
     *     )
     */
    public function startUpgradeAction($platformId, $buildingId)
    {
        $this->buildingService->startUpgrade($buildingId, $this->platformService, $this->appService);
        return $this->redirectToRoute('manage_buildings', ['id' => $platformId]);
    }
}
