<?php

namespace AppBundle\Controller;


use AppBundle\Form\UnitType as UnitForm;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTypeServiceInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * UnitController constructor.
     * @param UnitServiceInterface $unitService
     * @param UnitTypeServiceInterface $unitTypeService
     * @param BuildingServiceInterface $buildingService
     */
    public function __construct(UnitServiceInterface $unitService,
                                BuildingServiceInterface $buildingService,
                                AppServiceInterface $appService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService)
    {
        parent::__construct($appService, $gameStateService, $platformService);

        $this->unitService = $unitService;
        $this->buildingService = $buildingService;
    }


    /**
     * @Route("/settlement/{platformId}/units/all", name="manage_units")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction($platformId)
    {
        $platform = $this->getPlatform($platformId);
        $units = $this->unitService->getAllByPlatform($platform);

        return $this->render('unit/all.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'units' => $units,
        ]);
    }

    /**
     * @Route("/settlement/{platformId}/unit/type/{unitTypeId}", name="view_unit_type", requirements={"platformId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTypeAction(int $platformId,
                                   int $unitTypeId,
                                   UnitTypeServiceInterface $unitTypeService)
    {
        $platform = $this->getPlatform($platformId);
        $unitType = $unitTypeService->findById($unitTypeId);

        return $this->render('unit/show.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'unitType' => $unitType
        ]);
    }

    /**
     * @Route("/settlement/{platformId}/recruit/{unitId}", name="recruit",
     *     requirements={"platformId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recruit(int $platformId, int $unitId, Request $request)
    {
        $platform = $this->getPlatform($platformId);
        $unit = $this->unitService->getById($unitId);
        $form = $this->createForm(UnitForm::class, $unit);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            try {
                $this->unitService->startRecruiting($unit, $platform, $this->platformService);
            } catch (Exception $exception) {
                echo $exception->getMessage(); //todo flush messaging for both cases
            }

            return $this->redirectToRoute('recruit', ['platformId' => $platformId, 'unitId' => $unitId]);
        }

        return $this->render('unit/recruit.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'unit' => $unit,
            'form' => $form->createView()
        ]);
    }
}
