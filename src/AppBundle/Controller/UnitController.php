<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Form\UnitCountType;
use AppBundle\Form\UnitType as UnitForm;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
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
                                PlatformServiceInterface $platformService,
                                MessageServiceInterface $messageService)
    {
        parent::__construct($appService, $gameStateService, $platformService, $messageService);

        $this->unitService = $unitService;
        $this->buildingService = $buildingService;
    }


    /**
     * @Route("/settlement/{id}/units/all", name="manage_units", requirements={"id" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(int $id)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        return $this->render('unit/all.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'currentPage' => 'unit'
        ]);
    }

    /**
     * @Route("/settlement/{id}/unit/info/{unitId}", name="view_unit_type",
     *     requirements={"id" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showInfoAction(int $id, int $unitId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $unit = $this->platformService->getPlatfomUnit($unitId, $platform);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        return $this->render('unit/show.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'unitType' => $unit->getUnitType(),
            'currentPage' => 'unit',
        ]);
    }

    /**
     * @Route("/settlement/{id}/recruit/{unitId}", name="recruit",
     *     requirements={"id" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recruit(int $id, int $unitId, Request $request)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $unit = $this->platformService->getPlatfomUnit($unitId, $platform);

        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('edit', $unit);
        $this->updateState($platform);

        $form = $this->createForm(UnitCountType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            try {
                $this->unitService->startRecruiting($form->getData()['count'],
                                                    $unit,
                                                    $this->platformService);
            } catch (Exception $exception) {
                echo $exception->getMessage(); //todo flush messaging for both cases
            }

            return $this->redirectToRoute('recruit', [
                'id' => $platform->getId(),
                'unitId' => $unit->getId()
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
