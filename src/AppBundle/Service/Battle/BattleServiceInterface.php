<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\BattleReport;

interface BattleServiceInterface
{
    //public function processBattleJourneys(array $battleTasks): bool;

    //public function processBattle(ArmyJourney $journey): BattleReport;

    public function startBattle(ArmyJourney $journey): BattleReport;
}