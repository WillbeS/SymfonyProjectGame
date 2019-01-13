<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ResourceType;
use AppBundle\Service\Building\BuildingServiceInterface;

interface ResourceServiceInterface
{
    public function getResourceType(string $name): ?ResourceType;

    public function updateTotal(GameResource $resource, float $amount);

    public function createAllPlatformResources(Platform $platform,
                                               BuildingServiceInterface $buildingService);
}