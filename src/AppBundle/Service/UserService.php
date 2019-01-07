<?php

namespace AppBundle\Service;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
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


    public function register(
        PlatformServiceInterface $platformService,
        UserPasswordEncoderInterface $encoder,
        BuildingServiceInterface $buildingService,
        User $user)
    {
        $hashedPassword = $encoder->encodePassword(
            $user,
            $user->getPassword()
        );
        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);

        $platform = $platformService->getNewPlatform($buildingService, $user);

        $user->setPassword($hashedPassword)
            ->addRole($userRole)
            ->setCurrentPlatform($platform);

        $this->entityManager->persist($user);
        $this->entityManager->persist($platform);
        $this->entityManager->flush();
    }

    public function getPlatformId(int $id): int
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->find($id);

        return $user->getCurrentPlatform()->getId();
    }

    /**
     * @return User[]
     */
    public function viewAll(): array
    {
        return $this->userRepository->findAll();
    }
}