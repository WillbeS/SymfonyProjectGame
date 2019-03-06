<?php

namespace AppBundle\Service\Unit;

use AppBundle\Entity\Building\Building;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Unit;

interface UnitServiceInterface
{
    public function getById(int $id): Unit;

    public function getAllByPlatform(Platform $platform): array;

    public function updateUnitStatus(Platform $platform); // flushes


    /**
     * @param Building $building
     * @return Unit[]
     */
    public function getAllByBuilding(Building $building): array;
}