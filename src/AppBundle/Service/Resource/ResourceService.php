<?php

namespace AppBundle\Service\Resource;

use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ResourceType;
use AppBundle\Repository\ResourceRepository;
use AppBundle\Repository\ResourceTypeRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ResourceService implements ResourceServiceInterface
{
    const DEFAULT_STARTUP = 500;


    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ResourceRepository
     */
    private $resourceRepo;

    /**
     * @var ResourceTypeRepository
     */
    private $resourceTypeRepo;


    public function __construct(EntityManagerInterface $em,
                                ResourceRepository $resourceRepo,
                                ResourceTypeRepository $resourceTypeRepository)
    {
        $this->em = $em;
        $this->resourceRepo = $resourceRepo;
        $this->resourceTypeRepo = $resourceTypeRepository;
    }


    public function getResourceType(string $name): ?ResourceType
    {
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeRepo->findOneBy(['name' => $name]);
        return $resourceType;
    }

    public function updateTotal(GameResource $resource, float $amount)
    {
        $newTotal = $resource->getTotal() + $amount;

        if($newTotal < 0) {
            throw new Exception('Insufficient ' . $resource->getResourceType()->getName());
        }

        $resource
            ->setTotal($newTotal)
        ;
    }

    public function createAllPlatformResources(Platform $platform,
                                               BuildingServiceInterface $buildingService)
    {
        $resourceTypes = $this->resourceTypeRepo->findAll();

        foreach ($resourceTypes as $resourceType) {
            /** @var  ResourceType $resourceType */
            $resource = new GameResource();

            $building = $buildingService->getFromPlatformBuildingsByType($platform->getBuildings(),
                                                                        $resourceType->getGameBuilding());
            $resource
                ->setPlatform($platform)
                ->setResourceType($resourceType)
                ->setBuilding($building);

            $this->updateTotal($resource, self::DEFAULT_STARTUP);
            $platform->addResource($resource);
            $this->em->persist($resource);
        }
    }
}