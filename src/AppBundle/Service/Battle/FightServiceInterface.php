<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\BattleReport;

interface FightServiceInterface
{
    public function fight(ArmyModel $attackerArmy, ArmyModel $defenderArmy): ?BattleReport;
}