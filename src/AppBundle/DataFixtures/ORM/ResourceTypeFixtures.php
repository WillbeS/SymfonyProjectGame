<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Building\GameBuilding;
use AppBundle\Entity\ResourceType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ResourceTypeFixtures extends Fixture implements OrderedFixtureInterface
{
    const DEFAULT_RESOURCE_TYPES = [
        [
            'name' => 'Food',
            'baseIncome' => 5,
            'gameBuildingReference' => 'farm',
        ],
        [
            'name' => 'Wood',
            'baseIncome' => 5,
            'gameBuildingReference' => 'lumberMill',
        ],
        [
            'name' => 'Supplies',
            'baseIncome' => 15,
            'gameBuildingReference' => 'factory',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEFAULT_RESOURCE_TYPES as $resourceTypeInfo) {
            $resourceType = (new ResourceType())
                ->setName($resourceTypeInfo['name'])
                ->setBaseIncome($resourceTypeInfo['baseIncome'])
                ->setGameBuilding($this->getReference($resourceTypeInfo['gameBuildingReference']))
            ;

            $manager->persist($resourceType);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 400;
    }
}