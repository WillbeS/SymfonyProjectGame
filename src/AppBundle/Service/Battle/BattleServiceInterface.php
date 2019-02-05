<?php

namespace AppBundle\Service\Battle;


interface BattleServiceInterface
{
    public function processBattleJourneys(array $battleTasks): bool;
}