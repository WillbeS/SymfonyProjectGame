<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\GridCell;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\ParameterBag;

interface JourneyServiceInterface
{
    public function getAllOwnJourneys(GridCell $origin): array;

    public function getAllEnemyJourneys(GridCell $destination): array;

    public function processBattleJourneys(array $battleTasks): bool;
}