<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    const DEFAULT_ROLES = [
        'ROLE_ADMIN',
        'ROLE_USER',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEFAULT_ROLES as $roleName) {
            $role = (new Role())
                ->setName($roleName)
            ;

            $manager->persist($role);
            $this->addReference($roleName, $role);
        }

        $manager->flush();
    }
}