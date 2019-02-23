<?php

namespace AppBundle\Controller;

use AppBundle\Service\Map\MapGeneratorServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homeAction(MapGeneratorServiceInterface $mapGeneratorService)
    {
        $mapGeneratorService->generateMap(50);

        exit;

        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->redirectToRoute('platform_show', [
            'id' => $currentUser->getCurrentPlatform()->getId()
        ]);
    }
}
