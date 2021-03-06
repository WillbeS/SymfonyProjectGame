<?php

namespace AppBundle\Service\Battle;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\BattleReport;
use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\User;
use AppBundle\Entity\UserReport;
use Doctrine\ORM\EntityManagerInterface;

class BattleService implements BattleServiceInterface
{
    /**
     * @var ArmyModel
     */
    private $attackerArmy;

    /**
     * @var ArmyModel
     */
    private $defenderArmy;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ArmyServiceInterface
     */
    private $armyService;

    /**
     * @var BattleProcessServiceInterface
     */
    private $battleProcessService;


    public function __construct(EntityManagerInterface $em,
                                ArmyServiceInterface $armyService,
                                BattleProcessServiceInterface $battleProcessService)
    {
        $this->em = $em;
        $this->armyService = $armyService;
        $this->battleProcessService = $battleProcessService;
    }


    public function startBattle(MilitaryCampaign $campaign): BattleReport
    {
        $this->initBattle($campaign);

        $rounds = $this->battleProcessService->processBattle(
            $this->attackerArmy,
            $this->defenderArmy
        );

        $this->armyService->updateUnitCounts($this->attackerArmy);
        $this->armyService->updateUnitCounts($this->defenderArmy, true);

        return $this->createBattleReport($campaign, $rounds);
    }

    private function initBattle(MilitaryCampaign $campaign)
    {
        $attackerUnits = $campaign->getOrigin()->getUnits();
        $defenderUnits = $campaign->getDestination()->getUnits();
        $journeyTroops = json_decode($campaign->getArmy(), true);

        $this->attackerArmy = $this->armyService->initArmyModel($attackerUnits, $journeyTroops);
        $this->defenderArmy = $this->armyService->initArmyModel($defenderUnits);
    }

    private function createBattleReport(MilitaryCampaign $campaign, array $rounds): BattleReport
    {
        $attacker = $campaign->getOrigin()->getUser();
        $defender = $campaign->getDestination()->getUser();
        $winner = $this
            ->attackerArmy->getTotalHealth() > $this->defenderArmy->getTotalHealth() ?
            $attacker :
            $defender;

        $battleReport = new BattleReport();
        $battleReport
            ->setName("Battle Report - {$attacker->getUsername()} attacked {$defender->getUsername()}")
            ->setCreatedOn($campaign->getDueDate())
            ->setAttacker($attacker)
            ->setDefender($defender)
            ->setAttackerStartArmy(json_encode($this->attackerArmy->getOriginalTroopsCount()))
            ->setDefenderStartArmy(json_encode($this->defenderArmy->getOriginalTroopsCount()))
            ->setAttackerEndArmy($this->attackerArmy->getTroopsCountsJson())
            ->setDefenderEndArmy($this->defenderArmy->getTroopsCountsJson())
            ->setWinner($winner)
            ->setRounds(json_encode($rounds))
        ;

        $this->em->persist($battleReport);
        $this->createUserReport($battleReport, $battleReport->getAttacker());
        $this->createUserReport($battleReport, $battleReport->getDefender());

        return $battleReport;
    }

    private function createUserReport(BattleReport $battleReport, User $user)
    {
        $userReport = new UserReport();
        $userReport
            ->setIsRead(false)
            ->setUser($user)
            ->setReport($battleReport);

        $this->em->persist($userReport);
    }
}