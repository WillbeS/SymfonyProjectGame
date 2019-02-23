<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ResourceType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ResourceTypeFixtures extends Fixture implements DependentFixtureInterface
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
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
          GameBuildingFixtures::class
        ];
    }
}