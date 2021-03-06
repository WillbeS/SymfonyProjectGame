<?php

namespace AppBundle\Service\App;



use AppBundle\Entity\Platform;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;

interface GameStateServiceInterface
{
    public function updatePlatformState(): bool;

    //public function updateBuildingsState(Platform $platform = null);

    //public function updateUnitsInTrainingState(Platform $platform = null);

    public function updatePlatformResourcesState(Platform $platform,
                                                 PlatformServiceInterface $platformService);

}