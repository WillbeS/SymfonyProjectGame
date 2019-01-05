<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ResourceType;

interface ResourceServiceInterface
{
    public function getResourceType(string $name): ?ResourceType;

    public function getResource(string $name, Platform $platform);

    public function updateTotal(GameResource $resource, float $amount): GameResource;
}