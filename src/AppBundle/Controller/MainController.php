<?php

namespace AppBundle\Controller;

use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @var PlatformServiceInterface
     */
    protected $platformService; //todo - delete this


    public function __construct(GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService)
    {
        $this->platformService = $platformService;

        $gameStateService->updatePlatformState();
    }
}
