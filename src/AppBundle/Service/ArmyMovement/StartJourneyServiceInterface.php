<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\User;

interface StartJourneyServiceInterface
{
    public function startJourney(array $requestData, User $user, User $target): bool;

    public function startJourneyHome(string $troops, ArmyJourney $journey);
}