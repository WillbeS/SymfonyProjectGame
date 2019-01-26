<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Platform;
use Symfony\Component\Routing\Annotation\Route;


class PlatformController extends MainController
{
    /**
     * @Route("/settlement/{id}", name="platform_show")
     *
     * @param Platform $platform
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        return $this->render('platform/show.html.twig', [
            'currentPage' => 'platform'
        ]);
    }
}
