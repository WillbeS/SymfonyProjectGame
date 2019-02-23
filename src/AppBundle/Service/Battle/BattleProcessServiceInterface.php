<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\BattleReport;

interface BattleProcessServiceInterface
{
   public function processBattle(ArmyModel $attackArmy, ArmyModel $defenseArmy):array;
}