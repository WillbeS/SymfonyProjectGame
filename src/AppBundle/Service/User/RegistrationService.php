<?php

namespace AppBundle\Service\User;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationService implements RegistrationServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var PlatformCreationServiceInterface
     */
    private $platformCreationService;


    public function __construct( EntityManagerInterface $em,
                                 RoleRepository $roleRepository,
                                PlatformCreationServiceInterface $platformCreationService)
    {
        $this->em = $em;
        $this->roleRepository = $roleRepository;
        $this->platformCreationService = $platformCreationService;
    }


    public function register(User $user,
                             UserPasswordEncoderInterface $encoder)
    {
        $hashedPassword = $encoder->encodePassword($user, $user->getPassword());

        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);
        $platform = $this->platformCreationService->createPlatform($user);

        $user
            ->setPassword($hashedPassword)
            ->addRole($userRole)
            ->setCurrentPlatform($platform)
        ;

        $this->em->persist($user);
        $this->em->persist($platform);

        $this->em->flush();
    }
}