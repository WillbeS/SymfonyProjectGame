<?php

namespace AppBundle\Service;


use AppBundle\Entity\Message;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\MessageRepository;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Utils\EmDebuggerInterface;
use AppBundle\Service\Utils\FileServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var EmDebuggerInterface
     */
    private $emDebugger;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserRepository $userRepository,
                                RoleRepository $roleRepository,
                                MessageRepository $messageRepository,
                                EmDebuggerInterface $emDebugger)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->messageRepository = $messageRepository;
        $this->emDebugger = $emDebugger;
    }


    public function register(User $user,
                             PlatformServiceInterface $platformService,
                             UserPasswordEncoderInterface $encoder,
                             BuildingServiceInterface $buildingService,
                             UnitServiceInterface $unitService): User
    {
        $hashedPassword = $encoder->encodePassword($user, $user->getPassword());

        /** @var Role $userRole */
        $userRole = $this->roleRepository->findOneBy(['name' => 'ROLE_USER']);
        $platform = $platformService->getNewPlatform($buildingService, $unitService, $user);

        $user->setPassword($hashedPassword)
            ->addRole($userRole)
            ->setCurrentPlatform($platform);

        $this->entityManager->persist($user);
        $this->entityManager->persist($platform);
//
//       dump($this->emDebugger->getAllPersisted($this->entityManager));
//        exit;

        $this->entityManager->flush();
        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAllWithPlatform();
    }

    public function getById(int $id): User
    {
        $user = $this->userRepository->findOneWithPlatform($id);
        $this->assertFound($user);

        return $user;
    }

    public function editProfile(User $user, FileServiceInterface $fileService)
    {
        $file = $user->getFile();

        if (null !== $file) {
            $fileName = $fileService->upload($file);
            if (User::DEFAULT_AVATAR !== $user->getAvatar()) {
                $fileService->delete( $user->getAvatar());
            }

            $user->setAvatar($fileName);
        }
        $this->entityManager->flush();
    }

    //TODO - make it common for all services
    private function assertFound($entity)
    {
        if(!$entity) {

            throw new NotFoundHttpException('Page Not Found');
        }
    }
}