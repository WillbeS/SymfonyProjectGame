<?php

namespace AppBundle\Service\Battle;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\BattleReport;
use AppBundle\Entity\Unit;
use AppBundle\Entity\User;
use AppBundle\Entity\UserReport;
use AppBundle\Service\Utils\CountdownServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class BattleService implements BattleServiceInterface
{
    /**
     * @var bool
     */
    private $attackersTurn;

    /**
     * @var ArmyModel
     */
    private $attackerModel;

    /**
     * @var ArmyModel
     */
    private $defenderModel;

    /**
     * @var array
     */
    private $rounds = [];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * BattleService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, CountdownServiceInterface $countdownService)
    {
        $this->em = $em;
        $this->countdownService = $countdownService;
    }

    public function processBattleJourneys(array $journeys): bool
    {
        /** @var ArmyJourney $task */
        foreach ($journeys as $journey) {
            if (ArmyJourney::PURPOSE_RETURN === $journey->getPurpose()) {
                $this->processTroopsReturn($journey);
                continue;
            }

            $battleReport = $this->processBattle($journey);
            $this->createUserReport($battleReport, $battleReport->getAttacker());
            $this->createUserReport($battleReport, $battleReport->getDefender());

            $this->updateUnitCounts($this->attackerModel);
            $this->updateUnitCounts($this->defenderModel, true);

            if ($this->attackerModel->getTotalCount() > 0) {
                $this->setReturnJourney($journey, $battleReport);
            } else {
                $this->em->remove($journey);
            }
        }

        $this->em->flush();
        return true;
    }

    private function processBattle(ArmyJourney $journey): BattleReport
    {
        $this->initBattle($journey);
        $this->addRound();

        while (true)
        {
            if ($this->attackerModel->getTotalCount() <= 0 || $this->defenderModel->getTotalCount() <= 0) {
                break;
            }

            $this->fight();
            $this->addRound();
        }

        $this->addRound();

        return $this->createBattleReport($journey);
    }

    private function initBattle(ArmyJourney $journey)
    {
        $attackerUnits = $journey->getOrigin()->getPlatform()->getUnits();
        $defenderUnits = $journey->getDestination()->getPlatform()->getUnits();
        $journeyTroops = json_decode($journey->getTroops(), true);

        $this->attackerModel = $this->initArmyModels($attackerUnits, $journeyTroops);
        $this->defenderModel = $this->initArmyModels($defenderUnits);
        $this->attackersTurn = true;
        $this->rounds = [];
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



    private function setReturnJourney(ArmyJourney $journey, BattleReport $battleReport)
    {
        $dueDate = $this->countdownService->getEndDate($journey->getDueDate(), $journey->getDuration());
        $journey
            ->setPurpose(ArmyJourney::PURPOSE_RETURN)
            ->setName('Return from ' . $battleReport->getDefender()->getUsername())
            ->setStartDate($journey->getDueDate())
            ->setDueDate($dueDate)
            ->setTroops($this->attackerModel->getTroopsCountsJson());

        return $journey;
    }


    private function updateUnitCounts(ArmyModel $army, $isDefender = false)
    {
        /** @var Unit[] $units */
        $units = $army->getTroopsUnits();
        foreach ($units as $typeName => $unit) {
            $diff = $army->getOriginalTroopsCount()[$typeName] - $army->getTroopsCounts()[$typeName];

            if (0 == $diff) { continue; }
            if ($isDefender) {
                $unit->setIddle($unit->getIddle() - $diff);
            } else {
                $unit->setInBattle($unit->getInBattle() - $diff);
            }
        }
    }

    private function addRound()
    {
        $this->rounds[] = [
            'Attacker' => $this->attackerModel->getTroopsCounts(),
            'Defender' => $this->defenderModel->getTroopsCounts(),
        ];
    }

    private function createBattleReport(ArmyJourney $journey): BattleReport
    {
        $attacker = $journey->getOrigin()->getPlatform()->getUser();
        $defender = $journey->getDestination()->getPlatform()->getUser();
        $winner = $this->attackerModel->getTotalHealth() > 0 ? $attacker : $defender;

        $battleReport = new BattleReport();
        $battleReport
            ->setName("Battle Report - {$attacker->getUsername()} attacked {$defender->getUsername()}")
            ->setCreatedOn($journey->getDueDate())
            ->setAttacker($attacker)
            ->setDefender($defender)
            ->setAttackerStartArmy(json_encode($this->attackerModel->getOriginalTroopsCount()))
            ->setDefenderStartArmy(json_encode($this->defenderModel->getOriginalTroopsCount()))
            ->setAttackerEndArmy($this->attackerModel->getTroopsCountsJson())
            ->setDefenderEndArmy($this->defenderModel->getTroopsCountsJson())
            ->setWinner($winner)
            ->setRounds(json_encode($this->rounds))
        ;

        $this->em->persist($battleReport);
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

    private function fight()
    {
        if ($this->attackersTurn) {
            $this->defenderModel->processAttack($this->attackerModel->getTotalAttack());
        } else {
            $this->attackerModel->processAttack($this->defenderModel->getTotalAttack());
        }

        $this->attackersTurn = !$this->attackersTurn;
    }

    private function initArmyModels(Collection $units, array $journeyTroops = null): ArmyModel
    {
        $army = new ArmyModel();

        /** @var Unit $unit */
        foreach ($units as $unit) {
            if ($journeyTroops) {
                $count = (int)$journeyTroops[$unit->getUnitType()->getName()];
            } else {
                $count = $unit->getIddle();
            }

            $army->addTroops($count, $unit);
        }

        return $army;
    }
}