<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Platform;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PlatformDataService implements PlatformDataServiceInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var PlatformServiceInterface
     */
    private $platformService;

    /**
     * @var Platform
     */
    private $currentPlatform;


    public function __construct(RequestStack $requestStack,
                                AuthorizationCheckerInterface $authorizationChecker,
                                PlatformServiceInterface $platformService)
    {
        $this->requestStack = $requestStack;
        $this->authorizationChecker = $authorizationChecker;
        $this->platformService = $platformService;
    }


    public function getCurrentPlatform(): Platform
    {
        if (null === $this->currentPlatform) {
            $this->setCurrentPlatform();
        }

        return $this->currentPlatform;
    }

    //TODO - decide:
    // Maybe it's better to get platform from user (no need to be in the url) -
    // unless I plan to have more than 1 which I don't
    // so this here is unnecessary???
    private function setCurrentPlatform()
    {
        $platformId = $this->requestStack->getCurrentRequest()->attributes->getInt('id');

        if (null === $platformId) {
            throw new NotFoundHttpException('Page not found.');
        }

        //TODO decide if this something with less joins
        $platform = $this->platformService->getOneJoinedAll($platformId);
        if (!$this->authorizationChecker->isGranted('view', $platform)) {
            throw new AccessDeniedException();
        }

        $this->currentPlatform = $platform;
    }
}