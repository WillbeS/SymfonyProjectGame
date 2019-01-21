<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
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
                                MessageServiceInterface $messageService,
                                AppServiceInterface $appService)
    {
        parent::__construct($appService, $gameStateService, $platformService, $messageService); //TODO clean up when safe
        $this->buildingService = $buildingService;
    }

    /**
     * @Route("/settlement/{id}/building/view/{buildingId}", name="show_building_info",
     *     requirements={"platformId" = "\d+"}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showInfoAction(int $id, int $buildingId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $building = $this->platformService->getPlatfomBuilding($buildingId, $platform);

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
    public function manageAllAction(int $id)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
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
     * @Route("/settlement/{id}/building/upgrade/{buildingId}",
     *     name="start_upgrade",
     *     requirements={"platformId" = "\d+"}
     *     )
     */
    public function startUpgradeAction(int $id, int $buildingId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $building = $this->buildingService->getByIdJoined($buildingId);

        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('view', $building);
        $this->updateState($platform);

        try {
            $this->buildingService->startUpgrade($building,
                                                 $platform,
                                                 $this->platformService,
                                                 $this->appService);
        } catch (Exception $e) {
            var_dump($e->getMessage()); // TODO flush messaging
        }

        return $this->redirectToRoute('manage_buildings', ['id' => $platform->getId()]);
    }
}
