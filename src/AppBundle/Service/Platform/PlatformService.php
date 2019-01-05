<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\CustomData\PlatformData;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Repository\PlatformRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Map\MapServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class PlatformService implements PlatformServiceInterface
{
    //TODO - Delete these
    const DEFAULT_STARTUP_FOOD = 100;
    const DEFAULT_STARTUP_WOOD = 100;
    const DEFAULT_STARTUP_SUPPLIES = 100;

    const INCOME_FACTOR = .34;

    const UDATE_INTERVAL = 60;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PlatformRepository
     */
    private $platformRepossitory;

    /**
     * @var MapServiceInterface
     */
    private $mapService;

    /**
     * @var BuildingServiceInterface
     */
    private $buildingService;

    /**
     * @var ResourceServiceInterface
     */
    private $resourceService;

    /**
     * @var int[]
     */
    private $income;

    /**
     * @var boolean
     */
    private $platformChanged;




    public function __construct(EntityManagerInterface $entityManager,
                                PlatformRepository $platformRepository,
                                MapServiceInterface $mapService,
                                BuildingServiceInterface $buildingService,
                                ResourceServiceInterface $resourceService)
    {
        $this->entityManager = $entityManager;
        $this->platformRepossitory = $platformRepository;
        $this->mapService = $mapService;
        $this->buildingService = $buildingService;
        $this->resourceService = $resourceService;

        $this->income = [];
    }

    /**
     * @param MapServiceInterface $mapService
     * @param User|null $user
     * @return Platform
     */
    public function create(User $user = null)
    {
        $platform = $this->getNewPlatform($user);

        $this->entityManager->persist($platform);
        $this->entityManager->flush();

        return $platform;
    }

    public function findOneByUser(User $user): ?Platform
    {
        return $this->platformRepossitory
            ->findBy(['user' => $user], ['id' => 'ASC'], 1)[0];
    }

    public function getNewPlatform(User $user = null): ?Platform
    {
        $platform = new Platform();
        $this->addBuildings($platform);

        $platform
            ->setName('Old ruins')
            ->setGridCell($this->mapService->findAvailableGridCell());

        if (null !== $user) {
            $platform->setUser($user)
                ->setName('Settlement of ' . $user->getUsername());
        }

        $food = $this->resourceService->getResource('Food', $platform);
        $wood = $this->resourceService->getResource('Wood', $platform);
        $supplies = $this->resourceService->getResource('Supplies', $platform);

        $platform->setFood($food);
        $platform->setWood($wood);
        $platform->setSupplies($supplies);

        return $platform;
    }

    private function addBuildings(Platform $platform)
    {
        $gameBuildings = $this->buildingService->getGameBuildings();

        foreach ($gameBuildings as $gameBuilding) {
            $building = $this->buildingService->getNewBuilding($gameBuilding, $platform);

            $platform->addBuilding($building);
        }
    }



    public function getPlatformData(Platform $platform): PlatformData
    {
        $this->updateCurrentState($platform);
        $platformData = new PlatformData($platform);

        $platformData
            ->setFoodIncome($this->getIncome($platform->getFood()))
            ->setWoodIncome($this->getIncome($platform->getWood()))
            ->setSuppliesIncome($this->getIncome($platform->getSupplies()));

        return $platformData;
    }

    private function updateCurrentState(Platform $platform)
    {
        $this->platformChanged = false;
        $this->updateResource($platform->getWood());
        $this->updateResource($platform->getFood());
        $this->updateResource($platform->getSupplies());

        if($this->platformChanged) {
            $this->entityManager->persist($platform);
            $this->entityManager->flush();
        }
    }

    private function updateResource(GameResource $resource)
    {
        $now = new \DateTime('now');
        $elapsedSeconds = $now->getTimestamp() - $resource->getUpdateTime()->getTimestamp();

        if ($elapsedSeconds >= self::UDATE_INTERVAL) {
            $this->platformChanged = true;
            $amount = $this->getAmountForTime($this->getIncome($resource), $elapsedSeconds);

            $this->resourceService->updateTotal($resource, $amount);
        }
    }

    private function getAmountForTime(int $income, int $seconds): float
    {
        $incomePerSecond = $income/3600;
        $amount = $incomePerSecond * $seconds;

        return $amount;
    }

    private function getIncome(GameResource $resource): int
    {
        $resName = $resource->getResourceType()->getName();
        if (array_key_exists($resName, $this->income)) {
            return $this->income[$resName];
        }

        if(null == $resource->getBuilding()) {
            return 0;
        }

        $baseIncome = $resource->getResourceType()->getBaseIncome();
        $buildingLevel = $resource->getBuilding()->getLevel();

        $income = round($baseIncome + $baseIncome * $buildingLevel * self::INCOME_FACTOR);
        $this->income[$resName] = $income;

        return $income;
    }
}