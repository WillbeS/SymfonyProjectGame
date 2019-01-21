<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Time;

class MainController extends Controller
{
    //TODO - Important
    //TODO - check all connected entities for em->flush, should do it only once for all
    //TODO - check autorisation of all views
    //TODO - handle exceptions
    //TODO - mark all methods that flush (must be all public)

    /**
     * @var AppServiceInterface
     */
    protected $appService;

    /**
     * @var GameStateServiceInterface
     */
    protected $gameStateService;

    /**
     * @var PlatformServiceInterface
     */
    protected $platformService;

    /**
     * @var MessageServiceInterface
     */
    private $messageService;


    public function __construct(AppServiceInterface $appService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                MessageServiceInterface $messageService)
    {
        $this->appService = $appService;
        $this->platformService = $platformService;
        $this->gameStateService = $gameStateService;
        $this->messageService = $messageService;
    }

    // TODO - Move this into a service and call it with event
    protected function updateState(Platform $platform)
    {
        $this->gameStateService->updateBuildingsState($platform);
        $this->gameStateService->updatePlatformResourcesState($platform, $this->platformService);
        $this->gameStateService->updateUnitsInTrainingState($platform);
        $newMessagesCount = $this->messageService->getNewTopicsCount($this->getUser()->getId());

        $this->getUser()->setNewMessagesCount($newMessagesCount);
    }
}
