<?php

namespace AppBundle\Service\ScheduledTask;


use AppBundle\Entity\Building\Building;

class TimeCalculatorService implements TimeCalculatorServiceInterface
{

    public function calculateDurationByBuildingLevel(int $baseDuration, int $level): int
    {
        return floor($baseDuration + ($baseDuration * $level * Building::BUILD_TIME_FACTOR));
    }

    public function getTimeUntilDueDate(\DateTime $dueDate):int
    {
        $now = new \DateTime('now');
        return $dueDate->getTimestamp() - $now->getTimestamp();
    }
}