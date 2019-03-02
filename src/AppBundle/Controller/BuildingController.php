<?php

namespace AppBundle\Controller;


use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Building\BuildingUpgradeServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BuildingController
 * @package AppBundle\Controller
 *
 * @Route("/settlement/{id}", requirements={"id" = "\d+"})
 */
class BuildingController extends MainController
{
    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    public function __construct(PlatformServiceInterface $platformService,
                                GameStateServiceInterface $gameStateService,
                                BuildingServiceInterface $buildingService)
    {
        parent::__construct($gameStateService,
                            $platformService);

        $this->buildingService = $buildingService;
    }

    /**
     * @Route("/building/view/{buildingId}",
     *     name="show_building_info",
     *     requirements={"platformId" = "\d+"}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showInfoAction(int $buildingId)
    {
        $building = $this->buildingService->getByIdJoined($buildingId);
        $this->denyAccessUnlessGranted('view', $building);

        return $this->render('building/show.html.twig', [
            'building' => $building,
            'currentPage' => 'building'
        ]);
    }

    /**
     * @Route("/buildings/manage/", name="manage_buildings")
     */
    public function manageAllAction()
    {
        return $this->render('building/manage-all.html.twig', [
            'currentPage' => 'building'
        ]);
    }

    /**
     * @Route("/building/upgrade/{buildingId}",
     *     name="start_upgrade",
     *     requirements={"buildingId" = "\d+"}
     *     )
     */
    public function startUpgradeAction(int $id,
                                       int $buildingId,
                                       BuildingUpgradeServiceInterface $buildingUpgradeService,
                                       ScheduledTaskServiceInterface $scheduledTaskService)
    {
        $building = $this->buildingService->getByIdJoined($buildingId);
        $this->denyAccessUnlessGranted('view', $building);

        try {
            $buildingUpgradeService->startUpgrade(
                $building,
                $this->platformService,
                $scheduledTaskService
            );
        } catch (Exception $e) {
            var_dump($e->getMessage()); // TODO flush messaging
        }

        return $this->redirectToRoute('manage_buildings', ['id' => $id]);
    }
}
