<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;
use AppBundle\Repository\PlatformRepository;
use AppBundle\Service\Map\MapServiceInterface;
use AppBundle\Service\Resource\ResourceIncomeServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use AppBundle\Traits\AssertFound;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class PlatformService implements PlatformServiceInterface
{
    use AssertFound;

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
     * @var ResourceIncomeServiceInterface
     */
    private $resourceIncomeService;




    public function __construct(EntityManagerInterface $entityManager,
                                PlatformRepository $platformRepository,
                                MapServiceInterface $mapService,
                                ResourceServiceInterface $resourceService,
                                ResourceIncomeServiceInterface $resourceIncomeService)
    {
        $this->entityManager = $entityManager;
        $this->platformRepossitory = $platformRepository;
        $this->mapService = $mapService;
        $this->resourceService = $resourceService;
        $this->resourceIncomeService = $resourceIncomeService;
    }

    public function getById(int $id): Platform //TODO - delete if not in use
    {
        /**
         * @var Platform $platform
         */
        $platform = $this->platformRepossitory->find($id);
        $this->assertFound($platform);

        return $platform;
    }

    public function getOneJoinedAll(int $id): Platform
    {
        $result = $this->platformRepossitory->findOneJoinedAll($id);
        $this->assertFound($result);

        return $result;
    }

    public function getOneJoinedWithUnitsResources(int $id): Platform
    {
        $result = $this->platformRepossitory->findOneWithUnitsAndResources($id);
        $this->assertFound($result);

        return $result;
    }

    public function getPlatfomUnit(int $unitId, Platform $platform): Unit
    {
        $unit = $platform->getUnits()->filter(function (Unit $unit) use ($unitId) {
            return $unit->getId() === $unitId;
        })->first();

        $this->assertFound($unit);

        return $unit;
    }

    public function getPlatfomBuilding(int $buildingId, Platform $platform): Building
    {
        $building = $platform->getBuildings()->filter(function (Building $building) use ($buildingId) {
            return $building->getId() === $buildingId;
        })->first();

        $this->assertFound($building);

        return $building;
    }

    public function payPrice(Platform $platform, array $price)
    {
        foreach ($price as $resourceName => $value) {
            $resource = $this->findResourceByTypeName($resourceName, $platform->getResources());

            $this->resourceService->updateTotal($resource, $value * -1);
        }

        $platform->setResourceUpdateTime(new \DateTime('now'));
    }

    public function updateTotalResources(int $elapsed,
                                         Platform $platform)
    {
        foreach ($platform->getResources() as $resource) {
            $this->updateResource($elapsed, $resource);
        }

        $platform->setResourceUpdateTime(new \DateTime('now'));

        $this->entityManager->flush();
    }


    private function findResourceByTypeName(string $name, Collection $resources): GameResource
    {
        return $resources
            ->filter(function (GameResource $resource) use ($name) {
                return $resource->getResourceType()->getName() === $name;
            })
            ->first();
    }

    private function updateResource(int $elapsed,
                                    GameResource $resource)
    {
        $income = $this->resourceIncomeService->getIncomePerHour($resource);
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