<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\Unit;
use AppBundle\Service\App\GameNotificationException;
use AppBundle\Service\Utils\FlashMessageServiceInterface;
use Doctrine\Common\Collections\Collection;

class ArmyService implements ArmyServiceInterface
{
    /**
     * @var FlashMessageServiceInterface
     */
    private $flashMessageService;



    /**
     * @var int
     */
    private $slowestSpeed;

    /**
     * ArmyService constructor.
     * @param FlashMessageServiceInterface $flashMessageService
     */
    public function __construct(FlashMessageServiceInterface $flashMessageService)
    {
        $this->flashMessageService = $flashMessageService;
        $this->slowestSpeed = 0;
    }

    public function getArmyFromRequestData(array $requestData, Collection $platformUnits): string
    {
        $attackerArmy = [];

        /** @var Unit $unit */
        foreach ($platformUnits as $unit) {
            if (!array_key_exists($unit->getId(), $requestData)) {
                continue;
            }

            $count = $requestData[$unit->getId()];
            $this->setSlowestSpeed($count, $unit->getUnitType()->getSpeed());
            $this->setUnitsInBattle($count, $unit);
            $attackerArmy[$unit->getUnitType()->getName()] = (int)$count;
        }

        if (0 === $this->slowestSpeed) {
            throw new GameNotificationException('You must choose at least 1 unit');
        }

        return json_encode($attackerArmy);
    }

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

    public function getArmySpeed(): int
    {
        return $this->slowestSpeed;
    }

    private function setUnitsInBattle(int $count, Unit $unit)
    {
        if (0 == $count) {
            return;
        }

        $unit->setInBattle($unit->getInBattle() + $count);
        $unit->setIddle($unit->getIddle() - $count);
    }

    private function setSlowestSpeed(int $count, int $speed)
    {
        if (0 == $count || $speed < $this->slowestSpeed) {
            return;
        }

        $this->slowestSpeed = $speed;
    }
}