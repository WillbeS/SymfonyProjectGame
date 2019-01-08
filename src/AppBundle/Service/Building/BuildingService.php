<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ViewData\BuildingData;
use AppBundle\Repository\BuildingRepository;
use AppBundle\Repository\GameBuildingRepository;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class BuildingService implements BuildingServiceInterface
{
    const COST_FACTOR = 1.15;
    //const BUILD_TIME_FACTOR = 1.33;

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

    /**
     * @var Building
     */
    private $building;


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

    /**
     * @param Platform|null $platform
     * @return Building[]
     */
    public function getPending(Platform $platform = null): array
    {
        $pending = $this->buildingRepo->findPending($platform);
        return $pending;
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



    public function startUpgrade(int $id,
                                 PlatformServiceInterface $platformService,
                                 AppServiceInterface $appService)
    {
        //TODO - add maxLevel
        $building = $this->findById($id);

        if ($building->isPending()) {
            throw new Exception('Already is building.');
        }

        $platformService->payResources($building->getPlatform(),
                                        $building->getWoodCost($appService),
                                        $building->getFoodCost($appService),
                                        $building->getSuppliesCost($appService));

        $building
            ->setStartBuild(new \DateTime('now'));
        $this->em->flush();
    }

    public function finishBuilding(Building $building)
    {
        $building
            ->setStartBuild(null)
            ->setLevel($building->getLevel() + 1);

        $this->em->persist($building);
        $this->em->flush();
    }

}