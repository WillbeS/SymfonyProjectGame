<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GridCell;
use AppBundle\Service\Map\MapService;
use AppBundle\Service\Map\MapServiceInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MapController
 * @package AppBundle\Controller
 *
 * @Route("/settlement/{id}", requirements={"id" = "\d+"})
 */
class MapController extends MainController
{
    const VISIBLE_MAP_SIZE = 10;

    /**
     * @Route("/map/", name="map_all")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(int $id, MapServiceInterface $mapService)
    {
        //TODO - Refactor or delete
        $map = $this->getDoctrine()
            ->getRepository(GridCell::class)
            ->findBy([], ['row' => 'ASC', 'col' => 'ASC']);

        return $this->render('map/index.html.twig', [
            'map' => $map,
            'size' => MapService::MAP_SIZE,
            'currentPage' => 'map'
        ]);
    }

    /**
     * @Route("/map/local", name="map_local")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showLocalAction(MapServiceInterface $mapService)
    {
        /**
         * @var GridCell[] $map
         */
        $map = $mapService->findAllByDistrict(1);

        return $this->render('map/show-local.html.twig', [
            'map' => $map,
            'size' => self::VISIBLE_MAP_SIZE,
            'currentPage' => 'map'
        ]);
    }
}
