<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\ParameterBag;

interface StartJourneyServiceInterface
{
    public function startJourney(array $requestData, User $user, User $target): bool;
}