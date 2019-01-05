<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Repository\GameBuildingRepository;
use AppBundle\Repository\ProductionBuildingRepository;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Resource\ResourceService;
use AppBundle\Service\Resource\ResourceServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class BuildingService implements BuildingServiceInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GameBuildingRepository
     */
    private $gameBuildingRepo;

    /**
     * @var BuildingRepository
     */
    private $buildingRepo;


    public function __construct(GameBuildingRepository $gameBuildingRepo,
                                BuildingRepository $buildingRepository,
                                EntityManagerInterface $em)
    {
        $this->gameBuildingRepo = $gameBuildingRepo;
        $this->buildingRepo = $buildingRepository;
        $this->em = $em;
    }

    public function levelUp(Building $building, ResourceServiceInterface $resourceService)
    {
        $platform = $building->getPlatform();
        $availableWood = $platform->getWood()->getTotal();
        $availableFood = $platform->getFood()->getTotal();
        $availableSupplies = $platform->getSupplies()->getTotal();

        if ($building->getWoodCost() > $availableWood ||
            $building->getFoodCost() > $availableFood ||
            $building->getSuppliesCost() > $availableSupplies
        ) {
            //TODO Flush message
            dump('Not enough resources');
            return;
        }


        $platform
            ->setWood($resourceService
                ->updateTotal($platform->getWood(), $building->getWoodCost() * -1));

        $platform
            ->setFood($resourceService
                ->updateTotal($platform->getFood(), $building->getFoodCost() * -1));

        $platform
            ->setSupplies($resourceService
                ->updateTotal($platform->getSupplies(), $building->getSuppliesCost() * -1));


        //TODO - add maxLevel
        $newLevel = $building->getLevel() + 1;
        $building->setLevel($newLevel);
        $this->em->persist($building);
        $this->em->persist($platform);
        $this->em->flush();
    }

    /**
     * @return GameBuilding[]
     */
    public function getGameBuildings(): array
    {
        return $this->gameBuildingRepo->findAll();
    }


    /**
     * @param GameBuilding $gameBuilding
     * @param Platform $platform
     * @return Building
     */
    public function getNewBuilding(GameBuilding $gameBuilding, Platform $platform): Building
    {
        $building = new Building();
        $building
            ->setLevel(0)
            ->setPlatform($platform)
            ->setGameBuilding($gameBuilding);

        return $building;
    }

    public function getByGameBuilding(GameBuilding $gameBuilding): ?Building
    {
        $buildiing = $this->buildingRepo->findOneBy(['gameBuilding' => $gameBuilding]);
    }
}