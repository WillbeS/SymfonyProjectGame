<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Building\Building;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\UserServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends MainController
{
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction(UserServiceInterface $userService)
    {
        $platformId = $userService->getPlatformId($this->getUser()->getId());
        return $this->redirectToRoute('platform_show', ['id' => $platformId]);
    }

    /**
     * @Route("/settlement/{id}", name="platform_show")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(int $id)
    {
        $platform = $this->getPlatform($id);
        /** @var Building[] $buildings */
        $buildings = $platform->getBuildings();

        return $this->render('platform/show.html.twig', [
            'platform' => $platform,
            'buildings' => $buildings,
            'appService' => $this->appService,
        ]);
    }

    public function showAllAction()
    {
        //TODO - show all platforms
    }
}
