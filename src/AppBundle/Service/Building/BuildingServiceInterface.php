<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface BuildingServiceInterface
{
    ///////////////// GET FROM DATABASE////////////////////////////////////////////////////
    public function findById(int $id):Building;

    public function getByIdJoined($id):Building;

    /**
     * @param int $platformId
     * @return Building[]
     */
    public function getAllByPlatform(int $platformId): array;

    public function getByGameBuilding(GameBuilding $gameBuilding, Platform $platform): Building;

    ///////////////////////// New Registration Methods ////////////////////////////////
    public function createAllPlatformBuildings(Platform $platform);

    public function getFromPlatformBuildingsByType($buildings, GameBuilding $buildingType);
    /////////////////////////////////////////////////////////////////////////////////

}