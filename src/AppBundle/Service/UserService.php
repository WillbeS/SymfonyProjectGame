<?php

namespace AppBundle\Service;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserRepository $userRepository,
                                RoleRepository $roleRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }


    /**
     * @return User[]
     */
    public function viewAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function register(UserPasswordEncoderInterface $encoder, User $user)
    {
        $hashedPassword = $encoder->encodePassword(
            $user,
            $user->getPassword()
        );
        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);
        $user->setPassword($hashedPassword)
            ->addRole($userRole);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }
}