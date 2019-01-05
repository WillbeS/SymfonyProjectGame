<?php

namespace AppBundle\Service;

use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Repository\PlatformRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlatformService implements PlatformServiceInterface
{
    const DEFAULT_STARTUP_FOOD = 100;
    const DEFAULT_STARTUP_WOOD = 100;
    const DEFAULT_STARTUP_SUPPLIES = 100;

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




    public function __construct(EntityManagerInterface $entityManager,
                                PlatformRepository $platformRepository,
                                MapServiceInterface $mapService,
                                BuildingServiceInterface $buildingService)
    {
        $this->entityManager = $entityManager;
        $this->platformRepossitory = $platformRepository;
        $this->mapService = $mapService;
        $this->buildingService = $buildingService;
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
        $platform
            ->setName('Old ruins')
            ->setGridCell($this->mapService->findAvailableGridCell())
            ->setFood(self::DEFAULT_STARTUP_FOOD)
            ->setWood(self::DEFAULT_STARTUP_WOOD)
            ->setSupplies(self::DEFAULT_STARTUP_SUPPLIES);

        if (null !== $user) {
            $platform->setUser($user)
                ->setName('Settlement of ' . $user->getUsername());
        }

        return $platform;
    }
}