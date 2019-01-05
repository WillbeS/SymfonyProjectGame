<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ResourceType;
use AppBundle\Repository\ResourceRepository;
use AppBundle\Repository\ResourceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResourceService implements ResourceServiceInterface
{
    const DEFAULT_STARTUP = 1000;


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

    public function getResource(string $name, Platform $platform): GameResource
    {
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeRepo
            ->findOneBy(['name' => $name]);

        $building = $this->getBuilding($platform, $resourceType);
        $resource = new GameResource();
        $resource->setBuilding($building)
            ->setResourceType($resourceType);

        return $this->updateTotal($resource, self::DEFAULT_STARTUP);
    }

    private function getBuilding(Platform $platform, ResourceType $resourceType)
    {
        $building = null;

        foreach ($platform->getBuildings() as $platformBuilding) {
            if ($platformBuilding->getGameBuilding() === $resourceType->getGameBuilding()) {
                $building = $platformBuilding;
                break;
            }
        }

        return $building;
    }

    public function updateTotal(GameResource $resource, float $amount): GameResource
    {
        $resource->setTotal($resource->getTotal() + $amount)
            ->setUpdateTime(new \DateTime('now'));

        return $resource;
    }
}