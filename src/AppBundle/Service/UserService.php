<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Utils\FileServiceInterface;
use AppBundle\Traits\AssertFound;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService implements UserServiceInterface
{
    use AssertFound;

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
}