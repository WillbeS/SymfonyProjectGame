<?php

namespace AppBundle\Service\Battle;


interface BattleServiceInterface
{
    public function processBattles(array $battleTasks): bool;
}