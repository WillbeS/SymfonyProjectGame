<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GridCell;
use AppBundle\Service\Map\MapServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends MainController
{
    const VISIBLE_MAP_SIZE = 10;

    /**
     * @Route("/settlement/{id}/map/", name="map_all", requirements={"id" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(int $id, MapServiceInterface $mapService)
    {
        //TODO - Refactor or delete
        $platform = $this->getPlatform($id);
        $map = $this->getDoctrine()
            ->getRepository(GridCell::class)
            ->findBy([], ['row' => 'ASC', 'col' => 'ASC']);

        return $this->render('map/index.html.twig', [
            'map' => $map,
            'size' => 100,
            'platform' => $platform,
            'appService' => $this->appService,
        ]);
    }

    /**
     * @Route("/settlement/{id}/map/local", name="map_local", requirements={"id" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showLocalAction(int $id, MapServiceInterface $mapService)
    {
        $platform = $this->getPlatform($id);
        $map = $mapService->findAllByDistrict(1);

        return $this->render('map/show-local.html.twig', [
            'map' => $map,
            'size' => self::VISIBLE_MAP_SIZE,
            'platform' => $platform,
            'appService' => $this->appService,
        ]);
    }
}
