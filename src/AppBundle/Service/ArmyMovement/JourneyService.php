<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\GridCell;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\Unit;
use AppBundle\Repository\ArmyJourneyRepository;
use AppBundle\Repository\MilitaryCampaignRepository;
use AppBundle\Repository\ScheduledTaskRepository;
use AppBundle\Service\Battle\BattleServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use Doctrine\ORM\EntityManagerInterface;


class JourneyService implements JourneyServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ArmyJourneyRepository
     */
    private $armyJourneyRepository;

    /**
     * @var BattleServiceInterface
     */
    private $battleService;

    /**
     * @var StartJourneyServiceInterface
     */
    private $startJourneyService;

    /**
     * @var MilitaryCampaignRepository
     */
    private $militaryCampaigns;

    /**
     * BattleService constructor.
     * @param UnitServiceInterface $unitService
     */
    public function __construct(EntityManagerInterface $em,
                                ArmyJourneyRepository $armyJourneyRepository,
                                BattleServiceInterface $battleService,
                                StartJourneyServiceInterface $startJourneyService,
                                MilitaryCampaignRepository $militaryCampaignRepository)
    {
        $this->em = $em;
        $this->armyJourneyRepository = $armyJourneyRepository;
        $this->battleService = $battleService;
        $this->startJourneyService = $startJourneyService;
        $this->militaryCampaigns = $militaryCampaignRepository;
    }

    public function getAllOwnAttacks(Platform $origin): array
    {
        return $this->militaryCampaigns->findBy(['origin' => $origin]);
    }


    public function getAllEnemyAttacks(Platform $destination): array
    {
        return $this->militaryCampaigns->findBy(['destination' => $destination, 'taskType' => ScheduledTask::ATTACK_JOURNEY]);
    }


    /**
     * @param GridCell $origin
     * @return ArmyJourney[]
     */
    public function getAllOwnJourneys(GridCell $origin): array
    {
        return $this->armyJourneyRepository->findBy(['origin' => $origin]);
    }

    /**
     * @param GridCell $origin
     * @return ArmyJourney[]
     */
    public function getAllEnemyJourneys(GridCell $destination): array
    {
        return $this->armyJourneyRepository->findBy([
            'destination' => $destination,
            'purpose' => ArmyJourney::PURPOSE_BATTLE
        ]);
    }

    public function processBattleJourneys(array $journeys): bool
    {
        /** @var ArmyJourney $task */
        foreach ($journeys as $journey) {
            dump($journey);

            if (ArmyJourney::PURPOSE_RETURN === $journey->getPurpose()) {
                $this->processTroopsReturn($journey);
                continue;
            }

            $battleReport = $this->battleService->startBattle($journey);
//
            if ($battleReport->getWinner() === $battleReport->getAttacker()) {
                $troopsJson = json_encode($battleReport->getAttackerEndArmy());
                $this->startJourneyService->startJourneyHome($troopsJson, $journey);
            } else {
                $this->em->remove($journey);
            }
        }

        $this->em->flush();
        return true;
    }

    private function processTroopsReturn(ArmyJourney $journey)
    {
        $troops = json_decode($journey->getTroops(), true);
        $units = $journey->getOrigin()->getPlatform()->getUnits();
        /** @var Unit $unit */
        foreach ($units as $unit) {
            $count = $troops[$unit->getUnitType()->getName()];
            if (0 === $count) {
                continue;
            }

            $unit->setInBattle($unit->getInBattle() - $count);
            $unit->setIddle($unit->getIddle() + $count);
        }

        $this->em->remove($journey);
        $this->em->flush();
    }



}