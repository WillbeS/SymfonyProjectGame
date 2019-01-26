<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\User;
use AppBundle\Service\Message\MessageServiceInterface;
use Symfony\Component\Security\Core\Security;

class CommonDataService
{
    /** @var  Security */
    private $security;

    /**
     * @var MessageServiceInterface
     */
    private $messageService;


    public function __construct(Security $security,
                                MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
        $this->security = $security;
    }


    public function getNewMessagesCount(): int
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->messageService->getNewTopicsCount($user->getId());
    }
}