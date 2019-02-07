<?php

namespace AppBundle\Service\Battle;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\GridCell;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\ParameterBag;

interface JourneyServiceInterface
{
    //public function startJourney(ParameterBag $requestData, User $user, User $target): bool;

    public function getAllOwnJourneys(GridCell $origin): array;

    public function getAllEnemyJourneys(GridCell $destination): array;
}