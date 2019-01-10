<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/settlement/{id}/building/view/{building_id}", name="show_building",
     *     requirements={"platformId" = "\d+"}
     *     )
     *
     * @ParamConverter("building", class="AppBundle\Entity\Building\Building", options={"id" = "building_id"})
     *
     * @param PlatformServiceInterface $platformService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Platform $platform,
                               Building $building)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('view', $building);
        $this->updateState($platform);

        return $this->render('building/show.html.twig', [
            'platform' => $platform,
            'building' => $building,
            'appService' => $this->appService,
            'currentPage' => 'building'
        ]);
    }

    /**
     * @Route("settlement/{id}/buildings/manage/", name="manage_buildings", requirements={"id" = "\d+"})
     */
    public function manageAllAction(Platform $platform, TimerServiceInterface $timerService)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        /**
         * @var Building[] $buildings
         */
        $buildings = $platform->getBuildings();

        return $this->render('building/manage-all.html.twig', [
            'platform' => $platform,
            'buildings' => $buildings,
            'appService' => $this->appService,
            'currentPage' => 'building'
        ]);
    }

    /**
     * @Route("/settlement/{id}/building/upgrade/{building_id}",
     *     name="start_upgrade",
     *     requirements={"platformId" = "\d+"}
     *     )
     *
     *  @ParamConverter("building", class="AppBundle\Entity\Building\Building", options={"id" = "building_id"})
     */
    public function startUpgradeAction(Platform $platform, Building $building)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('view', $building);
        $this->updateState($platform);

        $this->buildingService->startUpgrade($building, $this->platformService, $this->appService);
        return $this->redirectToRoute('manage_buildings', ['id' => $platform->getId()]);
    }
}
