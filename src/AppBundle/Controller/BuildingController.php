<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building\Building;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends Controller
{
    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    public function __construct(BuildingServiceInterface $buildingService)
    {
        $this->buildingService = $buildingService;
    }


    /**
     * @Route("/building/edit/{id}", name="level_up")
     */
    public function editAction(Building $building, ResourceServiceInterface $resourceService)
    {
        $this->buildingService->levelUp($building, $resourceService);
        return $this->redirectToRoute('homepage');
    }
}
