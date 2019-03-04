<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\User;
use AppBundle\Repository\ArmyJourneyRepository;
use AppBundle\Repository\MilitaryCampaignRepository;
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
     * @var MilitaryCampaignRepository
     */
    private $militaryCampaignRepository;

    /**
     * @var User
     */
    private $currentUser;


    public function __construct(Security $security,
                                MessageServiceInterface $messageService,
                                UserReportRepository $userReportRepository,
                                MilitaryCampaignRepository $militaryCampaignRepository)
    {
        $this->messageService = $messageService;
        $this->security = $security;
        $this->userReportRepository = $userReportRepository;
        $this->militaryCampaignRepository = $militaryCampaignRepository;

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
        return $this->militaryCampaignRepository->getEnemyAttacksCount(
            $this->currentUser->getCurrentPlatform()->getId()
        );
    }
}