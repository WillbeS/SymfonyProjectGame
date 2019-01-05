<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\GameResource;
use AppBundle\Entity\Platform;

interface AppServiceInterface
{
    public function updateTotalResource(Platform $platform);
}