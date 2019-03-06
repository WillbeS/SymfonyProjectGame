<?php

namespace AppBundle\Service\Building;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\Platform;

interface BuildingServiceInterface
{
    /**
     * @param int $id
     * @return Building
     */
    public function findById(int $id):Building;

    /**
     * @param $id
     * @return Building
     */
    public function getByIdJoined($id):Building;

    /**
     * @param int $platformId
     * @return Building[]
     */
    public function getAllByPlatform(int $platformId): array;

    /**
     * @param GameBuilding $gameBuilding
     * @param Platform $platform
     * @return Building
     */
    public function getByGameBuilding(GameBuilding $gameBuilding,
                                      Platform $platform): Building;
}