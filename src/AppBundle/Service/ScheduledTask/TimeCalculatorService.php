<?php

namespace AppBundle\Service\ScheduledTask;


use AppBundle\Entity\Building\Building;

class TimeCalculatorService implements TimeCalculatorServiceInterface
{

    public function calculateDurationByBuildingLevel(int $baseDuration, int $level): int
    {
        return floor($baseDuration + ($baseDuration * $level * Building::BUILD_TIME_FACTOR));
    }
}