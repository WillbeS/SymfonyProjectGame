<?php

namespace AppBundle\Service\User;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ResourceType;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitType;
use AppBundle\Entity\User;
use AppBundle\Repository\GameBuildingRepository;
use AppBundle\Repository\ResourceTypeRepository;
use AppBundle\Repository\UnitTypeRepository;
use AppBundle\Service\Map\MapServiceInterface;
use AppBundle\Traits\Findable;

class PlatformCreationService implements PlatformCreationServiceInterface
{
    use Findable;

    const START_RESOURCE_AMOUNT = 1000;

    const USER_PLATFORM_NAME = 'Settlement of ';

    const NO_USER_PLATFORM_NAME = 'Old Ruins';

    /**
     * @var GameBuildingRepository
     */
    private $gameBuildingRepository;

    /**
     * @var ResourceTypeRepository
     */
    private $resourceTypeRepository;

    /**
     * @var UnitTypeRepository
     */
    private $unitTypeRepository;

    /**
     * @var MapServiceInterface
     */
    private $mapService;


    public function __construct(GameBuildingRepository $gameBuildingRepository,
                                MapServiceInterface $mapService,
                                ResourceTypeRepository $resourceTypeRepository,
                                UnitTypeRepository $unitTypeRepository)
    {
        $this->gameBuildingRepository = $gameBuildingRepository;
        $this->mapService = $mapService;
        $this->resourceTypeRepository = $resourceTypeRepository;
        $this->unitTypeRepository = $unitTypeRepository;
    }


    public function createPlatform(User $user): Platform
    {
        $platform = new Platform();
        $this->createAllPlatformBuildings($platform);
        $this->createAllPlatformResources($platform);
        $this->createAllPlatformUnits($platform);
        $platformName =
            null !== $user ?
            self::USER_PLATFORM_NAME . $user->getUsername() :
            self::NO_USER_PLATFORM_NAME;

        $platform
            ->setUser($user)
            ->setName($platformName)
            ->setGridCell($this->mapService->findAvailableGridCell())
            ->setResourceUpdateTime(new \DateTime('now'))
        ;

        return $platform;
    }

    private function createAllPlatformBuildings(Platform $platform)
    {
        $gameBuildings = $this->gameBuildingRepository->findAll();

        foreach ($gameBuildings as $gameBuilding) {
            $building = new Building();
            $building
                ->setLevel(0)
                ->setGameBuilding($gameBuilding);

            $platform->addBuilding($building);
        }
    }

    private function createAllPlatformResources(Platform $platform)
    {
        $resourceTypes = $this->resourceTypeRepository->findAll();

        /** @var  ResourceType[] $resourceTypes */
        foreach ($resourceTypes as $resourceType) {
            $resource = new GameResource();

            $building = $this->getBuildingByType(
                $platform->getBuildings(),
                $resourceType->getGameBuilding()
            );

            $resource
                ->setResourceType($resourceType)
                ->setBuilding($building)
                ->setTotal(self::START_RESOURCE_AMOUNT)
            ;

            $platform->addResource($resource);
        }
    }

    private function createAllPlatformUnits(Platform $platform)
    {
        $types = $this->unitTypeRepository->findAll();

        foreach ($types as $type) {
            $unit = $this->createUnit($type, $platform);
            $platform->addUnit($unit);
        }
    }

    public function createUnit(UnitType $unitType,
                                 Platform $platform): Unit
    {
        $building = $this->getBuildingByType($platform->getBuildings(), $unitType->getGameBuilding());
        $unit = new Unit();

        $unit
            ->setUnitType($unitType)
            ->setBuilding($building)
            ->addForTraining(0)
            ->setIddle(0)
            ->setInBattle(0)
            ->setIsAvailable(false);

        return $unit;
    }


    private function getBuildingByType($buildings, GameBuilding $buildingType)
    {
        $resourceBuilding = null;

        foreach ($buildings as $building) {
            /** @var $building Building */
            if ($building->getGameBuilding()->getId() == $buildingType->getId()) {
                $resourceBuilding = $building;
                break;
            }
        }

        $this->assertFound($resourceBuilding);
        return $resourceBuilding;
    }
}