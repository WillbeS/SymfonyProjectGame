<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\BattleReport;

//Simple fight strategy
class BattleProcessService implements BattleProcessServiceInterface
{
    const BASE_ATTACK_COEFFICIENT = 1.5;

    /**
     * @var array
     */
    private $rounds;

    public function processBattle(ArmyModel $attackArmy, ArmyModel $defenseArmy):array
    {
        $this->rounds = [];
        $this->addRound($attackArmy, $defenseArmy);

        while (true)
        {
            if ($attackArmy->getTotalCount() <= 0 ||
                $defenseArmy->getTotalCount() <= 0 ||
                count($this->rounds) >= 10)
            {
                break;
            }

            $this->fight($attackArmy, $defenseArmy);
            $this->addRound($attackArmy, $defenseArmy);
        }

        $this->addRound($attackArmy, $defenseArmy);

        return $this->rounds;
    }

    private function fight(ArmyModel $attackArmy, ArmyModel $defenseArmy)
    {
        $attackerHitPoints = $attackArmy->getTotalAttack();

        $defenderLosses =
            $attackerHitPoints *
            self::BASE_ATTACK_COEFFICIENT /
            $defenseArmy->getTotalHealth();

        $defenderHitPoints = $defenseArmy->getTotalAttack();

        $attackerLosses =
            $defenderHitPoints *
            self::BASE_ATTACK_COEFFICIENT /
            $attackArmy->getTotalHealth();

        $attackArmy->processAttack($attackerLosses);
        $defenseArmy->processAttack($defenderLosses);
    }


    private function addRound(ArmyModel $attackArmy, ArmyModel $defenseArmy)
    {
        $this->rounds[] = [
            'Attacker' => $attackArmy->getTroopsCounts(),
            'Defender' => $defenseArmy->getTroopsCounts(),
        ];
    }
}