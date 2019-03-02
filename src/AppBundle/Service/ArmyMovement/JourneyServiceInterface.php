<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\GridCell;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\ParameterBag;

interface JourneyServiceInterface
{
    public function getAllOwnAttacks(Platform $platform): array;

    public function getAllEnemyAttacks(Platform $platform): array;

    public function getAllOwnJourneys(GridCell $origin): array; //old

    public function getAllEnemyJourneys(GridCell $destination): array; //old

    public function processBattleJourneys(array $battleTasks): bool;
}