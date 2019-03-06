<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Utils\FileServiceInterface;

interface UserServiceInterface
{
    /**
     * @return User[]
     */
    public function getAll(): array;

    public function getById(int $id): User;

    public function editProfile(User $user, FileServiceInterface $fileService);
}