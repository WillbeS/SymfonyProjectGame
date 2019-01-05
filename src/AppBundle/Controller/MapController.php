<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GridCell;
use AppBundle\Service\Map\MapServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends Controller
{
    const VISIBLE_MAP_SIZE = 5;

    /**
     * @Route("/map", name="map_all")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(MapServiceInterface $mapService)
    {
        $map = $this->getDoctrine()
            ->getRepository(GridCell::class)
            ->findBy([], ['row' => 'ASC', 'col' => 'ASC']);

        return $this->render('map/index.html.twig', [
            'map' => $map,
            'size' => 100
        ]);
    }

    /**
     * @Route("/map/local", name="map_local")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showLocalAction(MapServiceInterface $mapService)
    {
        $map = $mapService->findAllByDistrict(1);

        return $this->render('map/show-local.html.twig', [
            'map' => $map,
            'size' => self::VISIBLE_MAP_SIZE
        ]);
    }
}
