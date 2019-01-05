<?php
/**
 * Created by PhpStorm.
 * User: CeXChester
 * Date: 03/01/2019
 * Time: 13:16
 */

namespace AppBundle\Service;


use AppBundle\Entity\Platform;
use AppBundle\Entity\User;

interface PlatformServiceInterface
{
    public function create(User $user = null);

    public function findOneByUser(User $user): ?Platform;

    public function getNewPlatform(User $user = null): ?Platform;
}