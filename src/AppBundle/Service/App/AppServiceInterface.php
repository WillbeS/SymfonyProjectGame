<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\GameResource;

interface AppServiceInterface
{
    public function getBuildTime(int $baseTime, $level): int;

    public function getBuildTimeFormated(int $baseTime, $level): string;

    public function getRemainingTime(\DateTime $startDate, int $baseTime, $level): int;

    public function getRemainingTimeNew(\DateTime $startDate, int $buildTime, int $count = 1): int;

    public function getCostPerLevel(int $baseCost, int $level): int;

    public function getIncomePerHour(GameResource $resource): int;

    public function formatTime(int $allSeconds): string;
}