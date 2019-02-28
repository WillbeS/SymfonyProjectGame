<?php

namespace AppBundle\Service\ScheduledTask;


interface TimeCalculatorServiceInterface
{
    public function calculateDurationByBuildingLevel(int $baseDuration, int $level): int;

    public function getTimeUntilDueDate(\DateTime $dueDate):int;
}