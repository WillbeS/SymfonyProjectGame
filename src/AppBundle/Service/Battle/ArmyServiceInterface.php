<?php

namespace AppBundle\Service\Battle;


use Doctrine\Common\Collections\Collection;

interface ArmyServiceInterface
{
    public function initArmyModel(Collection $units, array $journeyTroops = null): ArmyModel;

    public function updateUnitCounts(ArmyModel $army, $isDefender = false);
}