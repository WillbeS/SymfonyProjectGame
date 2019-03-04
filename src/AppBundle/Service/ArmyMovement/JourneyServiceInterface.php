<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\Platform;

interface JourneyServiceInterface
{
    public function getAllOwnAttacks(Platform $platform): array;

    public function getAllEnemyAttacks(Platform $platform): array;
}