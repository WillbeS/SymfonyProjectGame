<?php

namespace AppBundle\Service\ScheduledTask;


use AppBundle\Entity\Building\Building;

class TimeCalculatorService implements TimeCalculatorServiceInterface
{

    public function calculateUpgradeTimeByBuildingLevel(int $baseDuration, int $level): int
    {
        return floor($baseDuration + ($baseDuration * $level * Building::BUILD_TIME_FACTOR));
    }

    public function getTimeUntilDueDate(\DateTime $dueDate):int
    {
        $now = new \DateTime('now');
        return $dueDate->getTimestamp() - $now->getTimestamp();
    }

    public function formatTime(int $allSeconds): string
    {
        $seconds = $this->padZeroes($allSeconds % 60);
        $minutes = $this->padZeroes(floor($allSeconds / 60) % 60);
        $hours = $this->padZeroes(floor($allSeconds / 3600));

        return "$hours:$minutes:$seconds";
    }

    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int
    {
        if(null == $date2) {
            $date2 = new \DateTime('now');
        }

        return $date2->getTimestamp() - $date1->getTimestamp();
    }

    public function getDueDate(\DateTime $startDate, int $duration): \DateTime
    {
        $endTimestamp = $startDate->getTimestamp() + $duration;
        $date = new \DateTime();
        $date->setTimestamp($endTimestamp);

        return $date;
    }

    private function padZeroes($num): string
    {
        return str_pad($num, 2, '0', STR_PAD_LEFT);
    }
}