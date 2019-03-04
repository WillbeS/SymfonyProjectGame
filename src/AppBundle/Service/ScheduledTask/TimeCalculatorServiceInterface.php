<?php

namespace AppBundle\Service\ScheduledTask;


interface TimeCalculatorServiceInterface
{
    public function calculateUpgradeTimeByBuildingLevel(int $baseDuration, int $level): int;

    public function getTimeUntilDueDate(\DateTime $dueDate):int;

    public function formatTime(int $seconds): string;

    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int;

    public function getDueDate(\DateTime $startDate, int $duration): \DateTime;


}