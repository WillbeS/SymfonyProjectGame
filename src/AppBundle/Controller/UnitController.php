<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Form\UnitType as UnitForm;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTypeServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/settlement/{id}/units/all", name="manage_units", requirements={"id" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(Platform $platform)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        $units = $this->unitService->getAllByPlatform($platform);

        return $this->render('unit/all.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'units' => $units,
            'currentPage' => 'unit'
        ]);
    }

    /**
     * @Route("/settlement/{id}/unit/type/{unit_type_id}", name="view_unit_type",
     *     requirements={"id" = "\d+"})
     *
     * @ParamConverter("unitType", class="AppBundle\Entity\UnitType", options={"id" = "unit_type_id"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTypeAction(Platform $platform,
                                   \AppBundle\Entity\UnitType $unitType)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        return $this->render('unit/show.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'unitType' => $unitType,
            'currentPage' => 'unit',
        ]);
    }

    /**
     * @Route("/settlement/{id}/recruit/{unit_id}", name="recruit",
     *     requirements={"id" = "\d+"})
     *
     * @ParamConverter("unit", class="AppBundle\Entity\Unit", options={"id" = "unit_id"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recruit(Platform $platform, Unit $unit, Request $request)
    {
        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('edit', $unit); //TODO - check if this works for post method
        $this->updateState($platform);

        $form = $this->createForm(UnitForm::class, $unit);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            try {
                $this->unitService->startRecruiting($unit, $platform, $this->platformService);
            } catch (Exception $exception) {
                echo $exception->getMessage(); //todo flush messaging for both cases
            }

            return $this->redirectToRoute('recruit', [
                'id' => $platform->getId(),
                'unit_id' => $unit->getId()
            ]);
        }

        return $this->render('unit/recruit.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'unit' => $unit,
            'form' => $form->createView(),
            'currentPage' => 'unit'
        ]);
    }
}
