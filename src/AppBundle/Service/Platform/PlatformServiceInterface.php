<?php

namespace AppBundle\Service\Platform;

use AppBundle\Entity\CustomData\PlatformData;
use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;
use AppBundle\Entity\User;

interface PlatformServiceInterface
{
    public function create(User $user = null);

    public function findOneByUser(User $user): ?Platform;

    public function getNewPlatform(User $user = null): ?Platform;

    public function getPlatformData(Platform $platform): PlatformData;
}