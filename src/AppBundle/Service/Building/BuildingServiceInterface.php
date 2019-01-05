<?php

namespace AppBundle\Service;


use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\BuildingType;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;

interface BuildingServiceInterface
{
    public function getGameBuildings(): array;

    public function getBuildingsByPlatform(Platform $platform): array;
}