<?php

namespace AppBundle\Service\App;



use AppBundle\Entity\Platform;
use AppBundle\Service\Platform\PlatformServiceInterface;

interface GameStateServiceInterface
{
    public function updateBuildingsState(Platform $platform = null);

    public function updatePlatformResourcesState(Platform $platform,
                                                 PlatformServiceInterface $platformService);

    // TODO **For future commands use**
    public function updateResourceForAllPlatforms();
}