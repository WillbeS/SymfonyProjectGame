<?php

namespace AppBundle\Service\User;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface RegistrationServiceInterface
{
    public function register(User $user,
                             UserPasswordEncoderInterface $encoder);

}