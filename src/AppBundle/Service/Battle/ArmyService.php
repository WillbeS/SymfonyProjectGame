<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\Unit;
use Doctrine\Common\Collections\Collection;

class ArmyService implements ArmyServiceInterface
{
    public function initArmyModel(Collection $units, array $journeyTroops = null): ArmyModel
    {
        $army = new ArmyModel();

        /** @var Unit $unit */
        foreach ($units as $unit) {
            if ($journeyTroops) {
                $count = (int)$journeyTroops[$unit->getUnitType()->getName()];
            } else {
                $count = $unit->getIddle();
            }

            $army->addTroops($count, $unit);
        }

        return $army;
    }

    public function updateUnitCounts(ArmyModel $army, $isDefender = false)
    {
        /** @var Unit[] $units */
        $units = $army->getTroopsUnits();

        foreach ($units as $typeName => $unit) {
            $diff = $army->getOriginalTroopsCount()[$typeName] - $army->getTroopsCounts()[$typeName];

            if (0 == $diff) {
                continue;
            }

            if ($isDefender) {
                $unit->setIddle($unit->getIddle() - $diff);
            } else {
                $unit->setInBattle($unit->getInBattle() - $diff);
            }
        }
    }
}