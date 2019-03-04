<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Repository\GameBuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BuildingService implements BuildingServiceInterface
{
    const COST_FACTOR = 1.15;

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

    // TODO - remove this if not used
    public function findById(int $id): Building
    {
        /**
         * @var Building $building
         */
        $building = $this->buildingRepo->find($id);
        return $building;
    }

    public function getByIdJoined($id):Building
    {
        $building = $this->buildingRepo->findByIdJoined($id);

        if(null == $building) {
            throw new NotFoundHttpException('Page Not Found');
        }

        return $building;
    }

    /**
     * @param int $platformId
     * @return Building[]
     */
    public function getAllByPlatform(int $platformId): array
    {
        return $this->buildingRepo->findByPlatformJoined($platformId);
    }

    public function getByGameBuilding(GameBuilding $gameBuilding, Platform $platform): Building
    {
        /**
         * @var Building $building
         */
        $building = $this->buildingRepo->findOneBy([
            'gameBuilding' => $gameBuilding,
            'platform' => $platform
        ]);

        return $building;
    }

    /////////////////////////// For Registration ///////////////////////////////
    public function createAllPlatformBuildings(Platform $platform)
    {
        $gameBuildings = $this->gameBuildingRepo->findAll();

        foreach ($gameBuildings as $gameBuilding) {
            $building = new Building();
            $building
                ->setLevel(0)
                ->setGameBuilding($gameBuilding);

            $platform->addBuilding($building);
            $this->em->persist($building);
        }
    }

    public function getFromPlatformBuildingsByType($buildings, GameBuilding $buildingType)
    {
        foreach ($buildings as $building) {
            /** @var $building Building */
            if ($building->getGameBuilding()->getId() == $buildingType->getId()) {
                return $building;
            }
        }

        return null; //todo throw something (should always find one)
    }
}