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
use AppBundle\Service\Unit\UnitServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlatformService implements PlatformServiceInterface
{
    const INCOME_FACTOR = .34;
    //const UDATE_INTERVAL = 60; //seconds

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




    public function __construct(EntityManagerInterface $entityManager,
                                PlatformRepository $platformRepository,
                                MapServiceInterface $mapService,
                                ResourceServiceInterface $resourceService)
    {
        $this->entityManager = $entityManager;
        $this->platformRepossitory = $platformRepository;
        $this->mapService = $mapService;
        $this->resourceService = $resourceService;
    }

    public function getById(int $id): Platform
    {
        /**
         * @var Platform $platform
         */
        $platform = $this->platformRepossitory->find($id);

        return $platform;
    }

    public function getByIdJoined(int $id): Platform
    {
        $result = $this->platformRepossitory->findOneWithBuildings($id);

        if(null == $result) {
            throw new NotFoundHttpException('Page Not Found');
        }

        return $result;
    }

    public function getNewPlatform(BuildingServiceInterface $buildingService,
                                   UnitServiceInterface $unitService,
                                   User $user = null): ?Platform
    {
        $platform = new Platform();
        $buildingService->createAllPlatformBuildings($platform);
        $this->resourceService->createAllPlatformResources($platform, $buildingService);
        $unitService->createAllPlatformUnits($platform, $buildingService);

        if (null !== $user) {
            $platform->setUser($user)
                ->setName('Settlement of ' . $user->getUsername());
        }

        $platform
            ->setName('Old ruins')
            ->setGridCell($this->mapService->findAvailableGridCell())
            ->setResourceUpdateTime(new \DateTime('now'));

        return $platform;
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
                                         Platform $platform,
                                         AppServiceInterface $appService)
    {
        foreach ($platform->getResources() as $resource) {
            $this->updateResource($elapsed, $resource, $appService);
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