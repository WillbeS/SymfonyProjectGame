<?php

namespace AppBundle\Service\Building;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\BuildingType;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Resource\ResourceServiceInterface;

interface BuildingServiceInterface
{
    public function levelUp(Building $building, ResourceServiceInterface $resourceService);

    public function getGameBuildings(): array;

    public function getNewBuilding(GameBuilding $gameBuilding, Platform $platform): Building;

    public function getByGameBuilding(GameBuilding $gameBuilding): ?Building;
}