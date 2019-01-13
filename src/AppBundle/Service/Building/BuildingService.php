<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Repository\GameBuildingRepository;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
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

    /**
     * @var TimerServiceInterface
     */
    private $timerService;


    public function __construct(GameBuildingRepository $gameBuildingRepo,
                                BuildingRepository $buildingRepository,
                                EntityManagerInterface $em,
                                TimerServiceInterface $timerService)
    {
        $this->gameBuildingRepo = $gameBuildingRepo;
        $this->buildingRepo = $buildingRepository;
        $this->em = $em;
        $this->timerService = $timerService;
    }

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
     * @param Platform|null $platform
     * @return Building[]
     */
    public function getPending(Platform $platform = null): array
    {
        $pending = $this->buildingRepo->findPending($platform);
        return $pending;
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


    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 AppServiceInterface $appService)
    {
        //TODO - add maxLevel
        if ($building->isPending()) {
            return;
        }

        $platformService->payPrice($building->getPlatform(), $building->getPrice($appService));
        $building->setStartBuild(new \DateTime('now'));
        $this->em->flush();
    }

    public function finishBuilding(Building $building)
    {
        $building
            ->setStartBuild(null)
            ->setLevel($building->getLevel() + 1);

        $this->em->flush();
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