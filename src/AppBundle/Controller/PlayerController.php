<?php

namespace AppBundle\Controller;

use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Component\Routing\Annotation\Route;

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
                                AppServiceInterface $appService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                MessageServiceInterface $messageService)
    {
        parent::__construct($appService, $gameStateService, $platformService, $messageService); //TODO clean up when safe
        $this->userService = $userService;
    }


    /**
     * @Route("/settlement/{id}/player/all", name="players_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(int $id)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        $players = $this->userService->getAll();

        return $this->render('user/all.html.twig', [
            'currentPage' => 'player',
            'players' => $players,
            'platform' => $platform,
            'appService' => $this->appService
        ]);
    }
}
