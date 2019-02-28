<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\ScheduledTask\TimeCalculatorServiceInterface;
use AppBundle\Service\Utils\PriceCalculatorServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class BuildingUpgradeService implements BuildingUpgradeServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PriceCalculatorServiceInterface
     */
    private $priceCalculatorService;

    /**
     * @var TimeCalculatorServiceInterface
     */
    private $timeCalculatorService;

    /**
     * BuildingUpgradeService constructor.
     * @param PriceCalculatorServiceInterface $priceCalculatorService
     */
    public function __construct(EntityManagerInterface $em,
                                PriceCalculatorServiceInterface $priceCalculatorService,
                                TimeCalculatorServiceInterface $timeCalculatorService)
    {
        $this->em = $em;
        $this->priceCalculatorService = $priceCalculatorService;
        $this->timeCalculatorService = $timeCalculatorService;
    }


    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 ScheduledTaskServiceInterface $taskService)
    {
        $price = $this->getTotalPrice($building);
        $platformService->payPrice($building->getPlatform(), $price);

        $upgradeTask = $taskService->createTask(
            ScheduledTask::BUILDING_UPGRADE,
            $this->getUpgradeTimeDuration($building),
            $building
        );

        $this->em->persist($upgradeTask);
        $this->em->flush();
    }

    private function getUpgradeTimeDuration(Building $building)
    {
        return $this->timeCalculatorService->calculateDurationByBuildingLevel(
            $building->getGameBuilding()->getBuildTime(),
            $building->getLevel()
        );
    }

    private function getTotalPrice( Building $building): array
    {
        return [
            'Food' => $this->getPriceByResource(
                $building->getGameBuilding()->getFoodCost(), $building
            ),
            'Wood' => $this->getPriceByResource(
                $building->getGameBuilding()->getWoodCost(), $building
            ),
            'Supplies' => $this->getPriceByResource(
                $building->getGameBuilding()->getSuppliesCost(), $building
            ),
        ];
    }

    private function getPriceByResource(int $basePrice, Building $building)
    {
        return $this->priceCalculatorService->calculatePriceByLevel(
            $basePrice,
            $building->getLevel(),
            Building::COST_FACTOR
        );
    }

}