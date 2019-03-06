<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Repository\MessageRepository;
use AppBundle\Repository\RoleRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Utils\FileServiceInterface;
use AppBundle\Service\Utils\PersistedEntitiesServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements UserServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(EntityManagerInterface $em,
                                UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
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
        $this->em->flush();
    }

    //TODO - make it common for all services
    private function assertFound($entity)
    {
        if(!$entity) {

            throw new NotFoundHttpException('Page Not Found');
        }
    }
}