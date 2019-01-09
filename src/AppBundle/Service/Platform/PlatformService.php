<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Repository\PlatformRepository;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Map\MapServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class PlatformService implements PlatformServiceInterface
{
    const INCOME_FACTOR = .34;
    const UDATE_INTERVAL = 60; //seconds

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
     * @var ResourceServiceInterface
     */
    private $resourceService;

    /**
     * @var boolean
     */
    private $platformChanged;




    public function __construct(EntityManagerInterface $entityManager,
                                PlatformRepository $platformRepository,
                                MapServiceInterface $mapService,
                                ResourceServiceInterface $resourceService)
    {
        $this->entityManager = $entityManager;
        $this->platformRepossitory = $platformRepository;
        $this->mapService = $mapService;
        $this->resourceService = $resourceService;

        $this->income = [];
    }

    public function getById(int $id): Platform
    {
        /**
         * @var Platform $platform
         */
        $platform = $this->platformRepossitory->find($id);

        return $platform;
    }

//    public function findOneByUser(User $user): ?Platform
//    {
//        return $this->platformRepossitory
//            ->findBy(['user' => $user], ['id' => 'ASC'], 1)[0];
//    }

    public function getNewPlatform(BuildingServiceInterface $buildingService,
                                   User $user = null): ?Platform
    {
        $platform = new Platform();
        $this->addBuildings($platform, $buildingService);

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

        $platform
            ->setFood($food)
            ->setWood($wood)
            ->setSupplies($supplies)
            ->setResourceUpdateTime(new \DateTime('now'));

        return $platform;
    }

    private function addBuildings(Platform $platform, BuildingServiceInterface $buildingService)
    {
        $gameBuildings = $buildingService->getGameBuildings();

        foreach ($gameBuildings as $gameBuilding) {
            $building = $buildingService->getNewBuilding($gameBuilding, $platform);

            $platform->addBuilding($building);
        }
    }

    public function payResources(Platform $platform,
                                         $woodCost,
                                         $foodCost,
                                         $suppliesCost)
    {
        if($platform->getTotalWood() < $woodCost ||
           $platform->getTotalFood() < $foodCost ||
           $platform->getTotalSupplies() < $suppliesCost
        ) {
            throw new Exception('Insufficient resources');
        }

        $this->resourceService
            ->updateTotal($platform->getWood(), $woodCost * -1);
        $this->resourceService
            ->updateTotal($platform->getFood(), $foodCost * -1);
        $this->resourceService
            ->updateTotal($platform->getSupplies(), $suppliesCost * -1);

        $platform->setResourceUpdateTime(new \DateTime('now'));
    }

    public function updateTotalResources(int $elapsed,
                                         Platform $platform,
                                         AppServiceInterface $appService)
    {
        $this->updateResource($elapsed, $platform->getWood(), $appService);
        $this->updateResource($elapsed, $platform->getFood(), $appService);
        $this->updateResource($elapsed, $platform->getSupplies(), $appService);

        $platform->setResourceUpdateTime(new \DateTime('now'));

        $this->entityManager->persist($platform);
        $this->entityManager->flush();
    }

    private function updateResource(int $elapsed,
                                    GameResource $resource,
                                    AppServiceInterface $appService)
    {
        $income = $appService->getIncomePerHour($resource);
        $amount = $this->getAmountForTime($income, $elapsed);
        $this->resourceService->updateTotal($resource, $amount);
    }

    private function getAmountForTime(int $income, int $seconds): float
    {
        $incomePerSecond = $income/3600;
        $amount = $incomePerSecond * $seconds;

        return $amount;
    }

}