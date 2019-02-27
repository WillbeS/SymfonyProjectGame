<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\User;
use AppBundle\Repository\ArmyJourneyRepository;
use AppBundle\Repository\UserReportRepository;
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

    /**
     * @var UserReportRepository
     */
    private $userReportRepository;

    /**
     * @var ArmyJourneyRepository
     */
    private $armyJourneyRepository;

    /**
     * @var User
     */
    private $currentUser;


    public function __construct(Security $security,
                                MessageServiceInterface $messageService,
                                UserReportRepository $userReportRepository,
                                ArmyJourneyRepository $armyJourneyRepository)
    {
        $this->messageService = $messageService;
        $this->security = $security;
        $this->userReportRepository = $userReportRepository;
        $this->armyJourneyRepository = $armyJourneyRepository;

        $this->currentUser = $this->security->getUser();
    }


    public function getNewMessagesCount(): int
    {
        return $this->messageService->getNewTopicsCount($this->currentUser->getId());
    }

    public function getNewReportsCount(): int
    {
        return $this->userReportRepository->getNewReportsCount($this->currentUser->getId());
    }

    public function getEnemyAttacksCount(): int
    {
        return $this->armyJourneyRepository->getNewAttacksCount($this->currentUser
                                                                                ->getCurrentPlatform()
                                                                                ->getGridCell()
                                                                                ->getId());
    }
}