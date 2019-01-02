<?php
// src/DataFixtures/AppFixtures.php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Item;
use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const USER_ROLES = [
        'ROLE_MODERATOR',
        'ROLE_GUEST'
    ];

    const ITEMS = [
        [
            'name' => 'Tomatoe',
            'description' => 'Red, fresh',
            'energy' => 18
        ],
        [
            'name' => 'Apple',
            'description' => 'Red, fresh',
            'energy' => 28
        ],
        [
            'name' => 'Chicken',
            'description' => 'White meat, no fat',
            'energy' => 39
        ],
        [
            'name' => 'Olive Oil',
            'description' => '',
            'energy' => 822
        ],
    ];

    /////////////////////////////////////////////////////////////////////////////////////////////
    // Loading command:
    // php bin/console doctrine:fixtures:load --append

    // Help command
    // php bin/console doctrine:fixtures:load --help
    //////////////////////////////////////////////////////////////////////////////////////////////

    public function load(ObjectManager $manager)
    {
        //Load roles
//        foreach (self::USER_ROLES as $roleName) {
//            $role = new Role();
//            $role->setName($roleName);
//            $manager->persist($role);
//        }

        //load items
        foreach (self::ITEMS as $itemData) {
            $item = new Item();
            $item->setName($itemData['name']);
            $item->setDescription($itemData['description']);
            $item->setEnergy($itemData['energy']);
            $manager->persist($item);
        }

        $manager->flush();
    }
}