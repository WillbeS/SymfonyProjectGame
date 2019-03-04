<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Repository\MilitaryCampaignRepository;

class JourneyService implements JourneyServiceInterface
{
    /**
     * @var MilitaryCampaignRepository
     */
    private $militaryCampaignRepository;


    /**
     * JourneyService constructor.
     * @param MilitaryCampaignRepository $militaryCampaignRepository
     */
    public function __construct(MilitaryCampaignRepository $militaryCampaignRepository)
    {
        $this->militaryCampaignRepository = $militaryCampaignRepository;
    }

    public function getAllOwnAttacks(Platform $origin): array
    {
        return $this->militaryCampaignRepository->findBy(['origin' => $origin]);
    }


    public function getAllEnemyAttacks(Platform $destination): array
    {
        return $this->militaryCampaignRepository->findBy(
            [
                'destination' => $destination,
                'taskType' => ScheduledTask::ATTACK_JOURNEY
            ]
        );
    }
}