<?php

namespace AppBundle\Service\ScheduledTask;


interface TimeCalculatorServiceInterface
{
    public function calculateDurationByBuildingLevel(int $baseDuration, int $level): int;
}