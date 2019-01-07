<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface UserServiceInterface
{
    public function register(
        PlatformServiceInterface $platformService,
        UserPasswordEncoderInterface $encoder,
        BuildingServiceInterface $buildingService,
        User $user);

    public function getPlatformId(int $id): int;

    /**
     * @return User[]
     */
    public function viewAll(): array;
}