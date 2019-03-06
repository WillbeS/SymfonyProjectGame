<?php

namespace AppBundle\Service\User;


use AppBundle\Entity\Platform;
use AppBundle\Entity\User;

interface PlatformCreationServiceInterface
{
    public function createPlatform(User $user): Platform;
}