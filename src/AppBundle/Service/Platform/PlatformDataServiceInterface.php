<?php

namespace AppBundle\Service\Platform;


use AppBundle\Entity\Platform;

interface PlatformDataServiceInterface
{
    public function getCurrentPlatform(): Platform;
}