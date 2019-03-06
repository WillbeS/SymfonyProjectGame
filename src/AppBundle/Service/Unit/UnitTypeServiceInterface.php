<?php

namespace AppBundle\Service\Unit;

use AppBundle\Entity\Platform;
use AppBundle\Entity\UnitType;

interface UnitTypeServiceInterface
{
    public function findById(int $id): UnitType;

    public function findAll():array;

    public function isAvailable(UnitType $unitType,
                                Platform $platform): bool;
}