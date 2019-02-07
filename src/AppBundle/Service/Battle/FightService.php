<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\BattleReport;

//Simple fight strategy
class FightService implements FightServiceInterface
{
    const BASE_ATTACK_COEFFICIENT = 4;

    public function fight(ArmyModel $attackerArmy, ArmyModel $defenderArmy): ?BattleReport
    {
        $attackerHitPoints = $attackerArmy->getTotalAttack();

        $defenderLosses =
            $attackerHitPoints *
            self::BASE_ATTACK_COEFFICIENT /
            $defenderArmy->getTotalHealth();

        $defenderHitPoints = $defenderArmy->getTotalAttack();

        $attackerLosses =
            $defenderHitPoints *
            self::BASE_ATTACK_COEFFICIENT /
            $attackerArmy->getTotalHealth();

        $attackerArmy->processAttack($attackerLosses);
        $defenderArmy->processAttack($defenderLosses);

        return null;
    }
}