<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homeAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->redirectToRoute('platform_show', [
            'id' => $currentUser->getCurrentPlatform()->getId()
        ]);
    }

    /**
     * @Route("/settlement/{id}", name="platform_show")
     *
     * @param Platform $platform
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(int $id)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        /** @var Building[] $buildings */
        $buildings = $platform->getBuildings();

        return $this->render('platform/show.html.twig', [
            'platform' => $platform,
            'buildings' => $buildings,
            'appService' => $this->appService,
            'currentPage' => 'platform'
        ]);
    }
}
