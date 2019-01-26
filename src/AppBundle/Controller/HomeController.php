<?php

namespace AppBundle\Controller;

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
    public function homeAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->redirectToRoute('platform_show', [
            'id' => $currentUser->getCurrentPlatform()->getId()
        ]);
    }
}
