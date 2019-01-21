<?php

namespace AppBundle\Service;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Utils\FileServiceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface UserServiceInterface
{
    public function register(User $user,
                             PlatformServiceInterface $platformService,
                             UserPasswordEncoderInterface $encoder,
                             BuildingServiceInterface $buildingService,
                             UnitServiceInterface $unitService): User;


    /**
     * @return User[]
     */
    public function getAll(): array;

    public function getById(int $id): User;

    public function editProfile(User $user, FileServiceInterface $fileService);
}