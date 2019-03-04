<?php
namespace AppBundle\Twig;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Service\Resource\ResourceIncomeServiceInterface;
use AppBundle\Service\ScheduledTask\TimeCalculatorServiceInterface;
use AppBundle\Service\Utils\PriceCalculatorServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var TimeCalculatorServiceInterface
     */
    private $timeCalculatorService;

    /**
     * @var ResourceIncomeServiceInterface
     */
    private $resourceIncomeService;

    /**
     * @var PriceCalculatorServiceInterface
     */
    private $priceCalculatorService;


    public function __construct(TimeCalculatorServiceInterface $timeCalculatorService,
                                PriceCalculatorServiceInterface $priceCalculatorService,
                                ResourceIncomeServiceInterface $resourceIncomeService)
    {
        $this->timeCalculatorService = $timeCalculatorService;
        $this->priceCalculatorService = $priceCalculatorService;
        $this->resourceIncomeService = $resourceIncomeService;
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
            new TwigFunction('getTimeUntilDue', [$this, 'getTimeUntilDue']),
        ];
    }

    // Functions
    public function getIncomePerHour(GameResource $resource)
    {
        return $this->resourceIncomeService->getIncomePerHour($resource);
    }

    public function getCostPerLevel(int $basePrice, int $buildingLevel)
    {
        return $this->priceCalculatorService->calculatePriceByLevel(
            $basePrice,
            $buildingLevel,
            Building::COST_FACTOR
        );
    }

    public function getBuildTime(Building $building): string
    {
        return $this->timeCalculatorService->calculateUpgradeTimeByBuildingLevel(
            $building->getGameBuilding()->getBuildTime(),
            $building->getLevel()
        );
    }

    public function getTimeUntilDue(\DateTime $dueDate)
    {
        return $this->timeCalculatorService->getTimeUntilDueDate($dueDate);
    }


    // Filters
    public function formatTime(int $seconds)
    {
        return $this->timeCalculatorService->formatTime($seconds);
    }
}