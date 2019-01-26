<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\GameResource;
use AppBundle\Service\Utils\TimerServiceInterface;

//TODO - move to utilities and change name to something more appropriate
class AppService implements AppServiceInterface
{
    const COST_FACTOR = 1.15;
    const BUILD_TIME_FACTOR = 1.33;
    const INCOME_FACTOR = .34;

    /**
     * @var TimerServiceInterface
     */
    private $timerService;

    public function __construct(TimerServiceInterface $timerService)
    {
        $this->timerService = $timerService;
    }

    public function getBuildTime(int $baseTime, $level): int
    {
        return floor($baseTime + ($baseTime * $level * self::BUILD_TIME_FACTOR));
    }

    public function getBuildTimeFormated(int $baseTime, $level): string
    {
        return $this->timerService->formatTime($this->getBuildTime($baseTime, $level));
    }

    public function getCostPerLevel(int $baseCost, int $level): int
    {
        return floor($baseCost + ($baseCost * $level * self::COST_FACTOR));
    }

    //TODO - Move to ResourceService
    public function getIncomePerHour(GameResource $resource): int
    {
        if(null == $resource->getBuilding()) {
            return $resource->getResourceType()->getBaseIncome();
        }

        $baseIncome = $resource->getResourceType()->getBaseIncome();
        $buildingLevel = $resource->getBuilding()->getLevel();
        $income = round($baseIncome + $baseIncome * $buildingLevel * self::INCOME_FACTOR);

        return $income;
    }


    public function getRemainingTrainingTime(\DateTime $startDate, int $buildTime, int $count = 1): int
    {
        $elapsed = $this->timerService->getElapsedTime($startDate);

        return $buildTime * $count - $elapsed;
    }

    public function getRemainingTime(\DateTime $startDate, int $baseTime, $level): int
    {
        $buildTime = $this->getBuildTime($baseTime, $level);
        $elapsed = $this->timerService->getElapsedTime($startDate);

        return $buildTime - $elapsed;
    }

    //TODO - merge TimereService into this
    public function formatTime(int $allSeconds): string
    {
        $seconds = $this->padZeroes($allSeconds % 60);
        $minutes = $this->padZeroes(floor($allSeconds / 60) % 60);
        $hours = $this->padZeroes(floor($allSeconds / 3600));


        return "$hours:$minutes:$seconds";
    }

    private function padZeroes($num): string
    {
        return str_pad($num, 2, '0', STR_PAD_LEFT);
    }

}