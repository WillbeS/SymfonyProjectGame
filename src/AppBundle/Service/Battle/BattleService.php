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


    public function processBattles(array $journeyTasks): bool
    {
        /** @var ArmyJourney $task */
        foreach ($journeyTasks as $task) {
            if (ArmyJourney::PURPOSE_RETURN === $task->getPurpose()) {
                $this->processTroopsReturn($task);
                return true;
            }

            $this->initBattle($task);
            $battleReport = $this->processBattle($task);
            $attackerReport = $this->createUserReport($battleReport, $battleReport->getAttacker());
            $defenderReport = $this->createUserReport($battleReport, $battleReport->getDefender());
            $this->em->persist($battleReport);
            $this->em->persist($attackerReport);
            $this->em->persist($defenderReport);

            dump($battleReport);

            $this->updateUnitCounts($this->attackerModel);
            $this->updateUnitCounts($this->defenderModel, true);

            if ($this->attackerModel->getTotalCount() > 0) {
                $this->setReturnJourney($task, $battleReport);
            } else {
                $this->em->remove($task);
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
            $count = $troops[$unit->getId()];
            if (0 === $count) {
                continue;
            }

            $unit->setInBattle($unit->getInBattle() - $count);
            $unit->setIddle($unit->getIddle() + $count);
        }

        $this->em->remove($journey);
        $this->em->flush();
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

    private function setReturnJourney(ArmyJourney $journey, BattleReport $battleReport)
    {
        $dueDate = $this->countdownService->getEndDate($journey->getDueDate(), $journey->getDuration());
        $journey
            ->setPurpose(ArmyJourney::PURPOSE_RETURN)
            ->setName('Return from ' . $battleReport->getDefender()->getUsername())
            ->setStartDate($journey->getDueDate())
            ->setDueDate($dueDate)
            ->setTroops($this->getNewJourneyTroops());

        return $journey;
    }

    private function getNewJourneyTroops():string
    {
        $journeyTroops = [];
        /** @var Unit[] $units */
        $units = $this->attackerModel->getTroopsUnits();
        foreach ($units as $typeName => $unit) {
            $journeyTroops[$unit->getId()] = $this->attackerModel->getTroopsCounts()[$typeName];
        }

        return json_encode($journeyTroops);
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


    private function processBattle(ArmyJourney $journey): BattleReport
    {

        $battleReport = $this->createBattleReport($journey);

        dump($journey->getName());
        $round = 0;
        $this->rounds[$round] = [
            'Attacker' => $this->attackerModel->getTroopsCounts(),
            'Defender' => $this->defenderModel->getTroopsCounts(),
        ];

        while (true)
        {
            if ($this->attackerModel->getTotalCount() <= 0 || $this->defenderModel->getTotalCount() <= 0) {
                break;
            }

            $round++;
            $this->fight();
            $this->rounds[$round] = [
                'Attacker' => $this->attackerModel->getTroopsCounts(),
                'Defender' => $this->defenderModel->getTroopsCounts(),
            ];
        }

        $winner = $this->attackerModel->getTotalHealth() > 0 ?
            $journey->getOrigin()->getPlatform()->getUser() :
            $journey->getDestination()->getPlatform()->getUser();

        $battleReport
            ->setAttackerEndArmy($this->attackerModel->getTroopsCountsJson())
            ->setDefenderEndArmy($this->defenderModel->getTroopsCountsJson())
            ->setWinner($winner);

        $battleReport->setRounds(json_encode($this->rounds));
        return $battleReport;
    }

    private function createBattleReport(ArmyJourney $journey): BattleReport
    {
        $attacker = $journey->getOrigin()->getPlatform()->getUser();
        $defender = $journey->getDestination()->getPlatform()->getUser();
        $battleReport = new BattleReport();
        $battleReport
            ->setCreatedOn($journey->getDueDate())
            ->setAttacker($attacker)
            ->setDefender($defender)
            ->setAttackerStartArmy($this->attackerModel->getTroopsCountsJson())
            ->setDefenderStartArmy($this->defenderModel->getTroopsCountsJson())
            ->setName("Battle Report - {$attacker->getUsername()} attacked {$defender->getUsername()}")
        ;

        return $battleReport;
    }

    private function createUserReport(BattleReport $battleReport, User $user): UserReport
    {
        $userReport = new UserReport();
        $userReport
            ->setIsRead(false)
            ->setUser($user)
            ->setReport($battleReport);

        return $userReport;
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
                $count = (int)$journeyTroops[$unit->getId()];
            } else {
                $count = $unit->getIddle();
            }

            $army->addTroops($count, $unit);
        }

        return $army;
    }
}