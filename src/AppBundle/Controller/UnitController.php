<?php

namespace AppBundle\Controller;

use AppBundle\Form\UnitCountType;
use AppBundle\Service\App\GameNotificationException;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformDataServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTrainingServiceInterface;
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
     * @Route("/units/manage", name="manage_units")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manageAllAction()
    {
        return $this->render('unit/training-camp.html.twig', [
            'currentPage' => 'unit',
        ]);
    }


    /**
     * @Route("/unit/recruit/{unitId}",
     *     name="recruit_unit",
     *     requirements={"id" = "\d+", "unitId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recruitUnitAction(int $id,
                                      int $unitId,
                                      Request $request,
                                      UnitTrainingServiceInterface $unitTrainingService,
                                      ScheduledTaskServiceInterface $scheduledTaskService)
    {
        $form = $this->createForm(UnitCountType::class, null, [
            'action' => $this->generateUrl('recruit_unit', ['id' => $id, 'unitId' => $unitId]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $count = $form->getData()['count'];
            try {
                $this->validateCount($count);
                $unitTrainingService->startTraining(
                    $form->getData()['count'],
                    $unitId,
                    $this->platformService,
                    $scheduledTaskService
                );
            } catch (GameNotificationException $e) {
                $this->addFlash('danger', $e->getMessage());
            }

//            return $this->redirectToRoute('recruit', ['id' => $id, 'unitId' => $unitId]);
            return $this->redirectToRoute('manage_units', ['id' => $id]);
        }

        return $this->render('unit/recruit-unit.html.twig', [
            'form' => $form->createView(),
            'currentPage' => 'unit'
        ]);
    }

    private function validateCount($count)
    {
        if (null == $count ||
            $count <= 0 ||
            $count > PHP_INT_MAX)
        {
            throw new GameNotificationException('Count must be 1 or more.');
        }

    }
}
