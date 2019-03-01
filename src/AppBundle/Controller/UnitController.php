<?php

namespace AppBundle\Controller;

use AppBundle\Form\UnitCountType;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformDataServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTrainingServiceInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UnitController
 * @package AppBundle\Controller
 *
 * @Route("/settlement/{id}", requirements={"id" = "\d+"})
 */
class UnitController extends MainController
{
    /**
     * @var UnitServiceInterface
     */
    private $unitService;

    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;


    public function __construct(UnitServiceInterface $unitService,
                                BuildingServiceInterface $buildingService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService)
    {
        parent::__construct($gameStateService,
                            $platformService);

        $this->unitService = $unitService;
        $this->buildingService = $buildingService;
    }


    /**
     * @Route("/units/all", name="manage_units")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction()
    {
        return $this->render('unit/all.html.twig', [
            'currentPage' => 'unit'
        ]);
    }

    /**
     * @Route("/unit/info/{unitId}",
     *          name="view_unit_type",
     *          requirements={"unitId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showInfoAction(int $unitId, PlatformDataServiceInterface $platformDataService)
    {
        //TODO - complare this way of retrieving unit with retreiving building from db and decide which is better
        $platform = $platformDataService->getCurrentPlatform();
        $unit = $this->platformService->getPlatfomUnit($unitId, $platform);

        return $this->render('unit/show.html.twig', [
            'platform' => $platform,
            'unitType' => $unit->getUnitType(),
            'currentPage' => 'unit',
        ]);
    }

    /**
     * @Route("/recruit/{unitId}",
     *     name="recruit",
     *     requirements={"unitId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recruit(int $id, int $unitId,
                            Request $request,
                            PlatformDataServiceInterface $platformDataService,
                            UnitTrainingServiceInterface $unitTrainingService,
                            ScheduledTaskServiceInterface $scheduledTaskService)
    {
        $platform = $platformDataService->getCurrentPlatform();
        $unit = $this->platformService->getPlatfomUnit($unitId, $platform);
        $this->denyAccessUnlessGranted('edit', $unit);

        $form = $this->createForm(UnitCountType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $success = $unitTrainingService->startTraining(
                $form->getData()['count'],
                $unit,
                $this->platformService,
                $scheduledTaskService
            );

            if (!$success) {
                dump('Invalid count'); // todo flush
                exit;
            }

            return $this->redirectToRoute('recruit', ['id' => $id, 'unitId' => $unitId]);
        }

        return $this->render('unit/recruit.html.twig', [
            'platform' => $platform,
            'unit' => $unit,
            'form' => $form->createView(),
            'currentPage' => 'unit'
        ]);
    }
}
