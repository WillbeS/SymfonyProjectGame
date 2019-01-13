<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface BuildingServiceInterface
{
    ///////////////// GET FROM DATABASE////////////////////////////////////////////////////
    public function findById(int $id):Building;

    public function getByIdJoined($id):Building;

    public function getByGameBuilding(GameBuilding $gameBuilding, Platform $platform): Building;

    /**
     * @param Platform|null $platform
     * @return Building[]
     */
    public function getPending(Platform $platform = null): array;
    ////////////////////////////////////////////////////////////////////////////


    ////////////////// For Level upgrade/////////////////////////////////////////////
    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 AppServiceInterface $appService); //flushes

    public function finishBuilding(Building $building); //flushes
    //////////////////////////////////////////////////////////////////////////////////

    ///////////////////////// New Registration Methods ////////////////////////////////
    public function createAllPlatformBuildings(Platform $platform);

    public function getFromPlatformBuildingsByType($buildings, GameBuilding $buildingType);
    /////////////////////////////////////////////////////////////////////////////////

}