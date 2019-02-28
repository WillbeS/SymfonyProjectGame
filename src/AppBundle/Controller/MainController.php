<?php

namespace AppBundle\Controller;

use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @var GameStateServiceInterface
     */
    protected $gameStateService;

    /**
     * @var PlatformServiceInterface
     */
    protected $platformService;


    public function __construct(GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService)
    {
        $this->platformService = $platformService;
        $this->gameStateService = $gameStateService;

        $this->gameStateService->updatePlatformState();
    }
}
