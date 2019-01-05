<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Service\Platform\PlatformServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface UserServiceInterface
{
    public function register(
        PlatformServiceInterface $platformService,
        UserPasswordEncoderInterface $encoder,
        User $user);

    /**
     * @return User[]
     */
    public function viewAll(): array;
}