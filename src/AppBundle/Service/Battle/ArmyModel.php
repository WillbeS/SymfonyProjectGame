<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitType;


class ArmyModel
{
    const BASE_ATTACK_COEFFICIENT = 8;

    private $totalHealth;

    private $totalAttack;

    private $totalCount;

    private $originalTroopsCounts;

    private $troopsCounts;

    private $troopsUnits;

    public function __construct()
    {
        $this->resetArmyStats();
        $this->troopsCounts = [];
        $this->troopsUnits = [];
    }

    /**
     * @return mixed
     */
    public function getTotalHealth()
    {
        return $this->totalHealth;
    }

    /**
     * @return mixed
     */
    public function getTotalAttack()
    {
        return $this->totalAttack;
    }

    /**
     * @return mixed
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getTroopsCounts()
    {
        return $this->troopsCounts;
    }

    public function getOriginalTroopsCount()
    {
        return $this->originalTroopsCounts;
    }


    public function getTroopsCountsJson()
    {
        return json_encode($this->troopsCounts);
    }

    public function getTroopsUnits()
    {
        return $this->troopsUnits;
    }

    /**
     * @param array $troops
     */
    public function addTroops(int $count, Unit $unit)
    {
        $name = $unit->getUnitType()->getName();
        $this->troopsCounts[$name] = $count;
        $this->originalTroopsCounts[$name] = $count;
        $this->troopsUnits[$name] = $unit;
        $this->updateArmyStats($count, $unit->getUnitType());
    }

    public function processAttack(int $attackPoints)
    {
        $lossesPercent = $attackPoints * self::BASE_ATTACK_COEFFICIENT / $this->totalHealth;
        $this->resetArmyStats();
        foreach ($this->troopsCounts as $typeName => $unitCount) {
            $newCount = $unitCount - round($unitCount * $lossesPercent);
            $newCount = $newCount < 0 ? 0 : $newCount;
            $this->troopsCounts[$typeName] = $newCount;
            $this->updateArmyStats($newCount, $this->troopsUnits[$typeName]->getUnitType());
        }
    }

    private function updateArmyStats(int $count, UnitType $unitType)
    {
        $this->totalCount += $count;
        $this->totalHealth += $count * $unitType->getHealth();
        $this->totalAttack += $count * $unitType->getAttack();
    }

    private function resetArmyStats()
    {
        $this->totalCount = 0;
        $this->totalHealth = 0;
        $this->totalAttack = 0;
    }
}