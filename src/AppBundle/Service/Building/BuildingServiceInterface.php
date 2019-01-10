<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;

interface BuildingServiceInterface
{
    ///////////////// Common////////////////////////////////////////////////////
    public function findById(int $id):Building;

    public function getByGameBuilding(GameBuilding $gameBuilding, Platform $platform): Building;
    ////////////////////////////////////////////////////////////////////////////

    //////////////////////////For registration???////////////////////////////////////////
    public function getNewBuilding(GameBuilding $gameBuilding, Platform $platform): Building;

    public function getGameBuildings(): array; /////////////////maybe common???
    ////////////////////////////////////////////////////////////////////////////////////
    ///

    ///////////////// For State Update////////////////////////////////////////////////
    /**
     * @param Platform|null $platform
     * @return Building[]
     */
    public function getPending(Platform $platform = null): array;
    /////////////////////////////////////////////////////////////////////////////////


    ////////////////// For Level upgrade/////////////////////////////////////////////
    public function startUpgrade(Building $building,
                                 PlatformServiceInterface $platformService,
                                 AppServiceInterface $appService); //flushes

    public function finishBuilding(Building $building); //flushes
    //////////////////////////////////////////////////////////////////////////////////


}