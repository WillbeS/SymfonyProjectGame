<?php

namespace AppBundle\Controller;

use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    //TODO - Important
    //TODO - check all connected entities for em->flush, should do it only once for all
    //TODO - check autorisation of all views
    //TODO - handle exceptions
    //TODO - mark all methods that flush (must be all public - ?)

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
