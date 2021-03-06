<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\BattleReport;
use AppBundle\Entity\MilitaryCampaign;

interface BattleServiceInterface
{
    //public function processBattleJourneys(array $battleTasks): bool;

    //public function processBattle(ArmyJourney $journey): BattleReport;

    public function startBattle(MilitaryCampaign $campaign): BattleReport;
}