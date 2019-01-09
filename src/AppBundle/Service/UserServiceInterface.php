<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTypeServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface UserServiceInterface
{
    public function register(User $user,
                             PlatformServiceInterface $platformService,
                             UserPasswordEncoderInterface $encoder,
                             BuildingServiceInterface $buildingService,
                             UnitServiceInterface $unitService): User;

    public function getPlatformId(int $id): int;

    /**
     * @return User[]
     */
    public function viewAll(): array;
}