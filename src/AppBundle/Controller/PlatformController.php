<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\UserServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlatformController extends MainController
{
    /**
     * @Route("/", name="homepage")
     *
     * @param UserServiceInterface $userService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homeAction(UserServiceInterface $userService)
    {
        $platformId = $userService->getPlatformId($this->getUser()->getId());
        return $this->redirectToRoute('platform_show', ['id' => $platformId]);
    }

    /**
     * @Route("/settlement/{id}", name="platform_show")
     *
     * @param Platform $platform
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(int $id)
    {
        $platform = $this->platformService->getByIdJoined($id);

        $this->updateState($platform);
        $this->denyAccessUnlessGranted('view', $platform);

        /** @var Building[] $buildings */
        $buildings = $platform->getBuildings();

        return $this->render('platform/show.html.twig', [
            'platform' => $platform,
            'buildings' => $buildings,
            'appService' => $this->appService,
            'currentPage' => 'platform'
        ]);
    }

    public function showAllAction()
    {
        //TODO - show all platforms
    }
}
