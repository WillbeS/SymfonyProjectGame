<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\ResourceType;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;

use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends Controller
{
    /**
     * @var PlatformServiceInterface
     */
    private $platformService;

    /**
     * @var AppServiceInterface
     */
    private $appService;

    public function __construct(
        AppServiceInterface $appService,
        PlatformServiceInterface $platformService)
    {
        $this->appService = $appService;
        $this->platformService = $platformService;
    }

    //Important TODOS
    //TODO - change total res type to float

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        $platform = $this->platformService->findOneByUser($this->getUser());
        $settlement = $this->platformService->getPlatformData($platform);

        /** @var Building[] $buildings */
        $buildings = $platform->getBuildings();


        return $this->render('platform/show.html.twig', [
            'settlement' => $settlement,
            'buildings' => $buildings,
        ]);
    }
}
