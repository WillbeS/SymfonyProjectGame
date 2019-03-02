<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\ScheduledTask\TimeCalculatorServiceInterface;
use AppBundle\Service\Utils\PriceCalculatorServiceInterface;
use AppBundle\Traits\Findable;
use Doctrine\ORM\EntityManagerInterface;

class BuildingUpgradeService implements BuildingUpgradeServiceInterface
{
    use Findable;

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
     * @var BuildingRepository
     */
    private $buildingRepository;

    /**
     * BuildingUpgradeService constructor.
     * @param PriceCalculatorServiceInterface $priceCalculatorService
     */
    public function __construct(EntityManagerInterface $em,
                                PriceCalculatorServiceInterface $priceCalculatorService,
                                TimeCalculatorServiceInterface $timeCalculatorService,
                                BuildingRepository $buildingRepository)
    {
        $this->em = $em;
        $this->priceCalculatorService = $priceCalculatorService;
        $this->timeCalculatorService = $timeCalculatorService;
        $this->buildingRepository = $buildingRepository;
    }


    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 ScheduledTaskServiceInterface $taskService)
    {
        $price = $this->getTotalPrice($building);
        $platformService->payPrice($building->getPlatform(), $price);

        $upgradeTask = $taskService->createPlatformUnitTask(
            ScheduledTask::BUILDING_UPGRADE,
            $this->getUpgradeTimeDuration($building),
            $building
        );

        $building->setUpgradeTask($upgradeTask);

        $this->em->persist($upgradeTask);
        $this->em->flush();
    }

    public function finishUpgrade(ScheduledTaskInterface $upgradeTask)
    {
        /**
         * @var Building $building
         */
        $building = $this->buildingRepository->findOneBy(['upgradeTask' => $upgradeTask]);
        $this->assertFound($building);

        $building
            ->setUpgradeTask(null)
            ->setLevel($building->getLevel() + 1) //todo upgradeLevel method in the entity
        ;

        $this->em->remove($upgradeTask);
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