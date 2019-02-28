<?php
namespace AppBundle\Twig;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Unit;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\ScheduledTask\TimeCalculatorServiceInterface;
use AppBundle\Service\Utils\CountdownServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var AppServiceInterface
     */
    private $appService;

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * @var TimeCalculatorServiceInterface
     */
    private $timeCalculationService;

    /**
     * AppExtension constructor.
     * @param AppServiceInterface $appService
     */
    public function __construct(AppServiceInterface $appService,
                                CountdownServiceInterface $countdownService,
                                TimeCalculatorServiceInterface $timeCalculatorService)
    {
        $this->appService = $appService;
        $this->countdownService = $countdownService;
        $this->timeCalculationService = $timeCalculatorService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('formatTime', [$this, 'formatTime']),
        ];
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('getIncome', [$this, 'getIncomePerHour']),
            new TwigFunction('getCostPerLevel', [$this, 'getCostPerLevel']),
            new TwigFunction('getBuildTime', [$this, 'getBuildTime']),
            new TwigFunction('getRemainingBuildTime', [$this, 'getRemainingBuildTime']),
            new TwigFunction('getRemainingTrainingTime', [$this, 'getRemainingTrainingTime']),
            new TwigFunction('getRemainingTime', [$this, 'getRemainingTime']),
            new TwigFunction('getTimeUntilDue', [$this, 'getTimeUntilDue']),
        ];
    }

    // Functions
    public function getIncomePerHour(GameResource $resource)
    {
        return $this->appService->getIncomePerHour($resource);
    }

    public function getCostPerLevel(int $baseResourceCost, int $buildingLevel)
    {
        return $this->appService
            ->getCostPerLevel($baseResourceCost, $buildingLevel);
    }

    public function getBuildTime(Building $building): string
    {
        $baseTime = $building->getGameBuilding()->getBuildTime();
        return $this->appService->getBuildTime($baseTime, $building->getLevel());
    }

    public function getRemainingBuildTime(Building $building): string
    {
        return $this->appService->getRemainingTime($building->getStartBuild(),
                                                    $building->getGameBuilding()->getBuildTime(),
                                                    $building->getLevel());
    }

    public function getRemainingTrainingTime(Unit $unit): int
    {
        return $this->appService->getRemainingTrainingTime($unit->getStartBuild(),
                                                            $unit->getUnitType()->getBuildTime(),
                                                            $unit->getInTraining());
    }

    public function getRemainingTime(\DateTime $startDate, int $duration = null)
    {
        return $this->countdownService->getRemainingTime($startDate, $duration);
    }

    //TODO - refactor on all and make this the only way
    public function getTimeUntilDue(\DateTime $dueDate)
    {
        return $this->timeCalculationService->getTimeUntilDueDate($dueDate);
    }



    // Filters
    public function formatTime(int $seconds)
    {
        return $this->appService->formatTime($seconds);
    }
}