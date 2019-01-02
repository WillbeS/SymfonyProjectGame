<?php

namespace AppBundle\Service;

use AppBundle\Entity\Grid;
use AppBundle\Entity\Platform;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\EventListener\ProfilerListener;
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
        MapServiceInterface $mapService,
        UserPasswordEncoderInterface $encoder,
        User $user)
    {
        $hashedPassword = $encoder->encodePassword(
            $user,
            $user->getPassword()
        );
        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);

        $platform = new Platform();
        $platform->setName('Settlement of ' . $user->getUsername())
            ->setUser($user)
            ->setHealth(100)
            ->setGrid($this->getRandomGridCell($mapService->findAvailableByDistrict(1)));


        $user->setPassword($hashedPassword)
            ->addRole($userRole)
            ->addPlatform($platform);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @return User[]
     */
    public function viewAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param array $grid
     * @return Grid
     */
    private function getRandomGridCell(array $grid): Grid
    {
        $max = count($grid) - 1;
        return $grid[rand(0, $max)];
    }
}