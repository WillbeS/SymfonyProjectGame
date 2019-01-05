<?php

namespace AppBundle\Service;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\BuildingType;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Building\ProductionBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Repository\GameBuildingRepository;
use AppBundle\Repository\ProductionBuildingRepository;
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
     * @var ProductionBuildingRepository
     */
    private $productionBuildingRepo;

    public function __construct(ProductionBuildingRepository $productionBuildingRepository,
                                GameBuildingRepository $gameBuildingRepo,
                                EntityManagerInterface $em)
    {
        $this->productionBuildingRepo = $productionBuildingRepository;
        $this->gameBuildingRepo = $gameBuildingRepo;
        $this->em = $em;
    }

    /**
     * @return GameBuilding[]
     */
    public function getGameBuildings(): array
    {
        return $this->gameBuildingRepo->findAll();
    }

    public function getBuildingsByPlatform(Platform $platform): array
    {
        $buildings = [];
        //TODO
    }



//    public function getNewBuilding(GameBuilding $type, Platform $platform): Building
//    {
//        $existing = $this->findByPlatformAndType($platform, $type);
//        if ($existing) {
//            return $existing;
//        }
//
//        $building = new Building();
//        $building
//            ->setType($type)
//            ->setLevel(0)
//            ->setPlatform($platform);
//
//        return $building;
//    }



//    private function findByPlatformAndType(Platform $platform, GameBuilding $type): ?Building
//    {
//        return $this->buildingRepository
//            ->findOneBy([
//                'platform' => $platform,
//                'type' => $type
//            ]);
//    }

}