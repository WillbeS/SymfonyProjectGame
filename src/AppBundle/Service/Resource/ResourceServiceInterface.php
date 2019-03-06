<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\ResourceType;

interface ResourceServiceInterface
{
    public function getResourceType(string $name): ?ResourceType;

    public function updateTotal(GameResource $resource, float $amount);
}