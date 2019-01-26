<?php

namespace AppBundle\Controller;

use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

//TODO - reconsider if this is needed, already have second thoughts
class PlayerController extends MainController
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                MessageServiceInterface $messageService,
                                RequestStack $requestStack,
                                Security $security)
    {
        parent::__construct($gameStateService,
                            $platformService);

        $this->userService = $userService;
    }


    /**
     * @Route("/settlement/{id}/player/all", name="players_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction()
    {
        $players = $this->userService->getAll();

        return $this->render('user/all.html.twig', [
            'currentPage' => 'player',
            'players' => $players,
        ]);
    }
}
