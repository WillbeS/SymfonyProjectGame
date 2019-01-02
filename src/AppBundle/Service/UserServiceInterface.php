<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;

interface UserServiceInterface
{
    public function register(
        MapServiceInterface $mapService,
        UserPasswordEncoderInterface $encoder,
        User $user);

    /**
     * @return User[]
     */
    public function viewAll(): array;

    public function getUsername();
}