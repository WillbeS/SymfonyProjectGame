<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UnitType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UnitTypeFixtures extends Fixture implements DependentFixtureInterface
{
    const DEFAULT_UNIT_TYPES = [
        [
            'name' => 'Zombie Killer',
            'woodCost' => 5,
            'foodCost' => 15,
            'suppliesCost' => 10,
            'buildTime' => 135,
            'speed' => 2,
            'health' => 80,
            'attack' => 15,
            'defense' => 4,
            'description' => 'Killing zombies is an art and your zombie killers have mastered it! If you need someone to go outside your walls, these are your guys.',
            'gameBuildingReference' => 'trainingCamp',
            'referenceName' => 'zombieKiller',
        ],
        [
            'name' => 'Guard',
            'woodCost' => 15,
            'foodCost' => 10,
            'suppliesCost' => 5,
            'buildTime' => 80,
            'speed' => 1,
            'health' => 110,
            'attack' => 5,
            'defense' => 12,
            'description' => 'They are the reason your settlers are still alive. When it comes to defense, your guards are the best.',
            'gameBuildingReference' => 'trainingCamp',
            'referenceName' => 'guard',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEFAULT_UNIT_TYPES as $unitTypeInfo) {
            $unitType = (new UnitType())
                ->setName($unitTypeInfo['name'])
                ->setFoodCost($unitTypeInfo['foodCost'])
                ->setWoodCost($unitTypeInfo['woodCost'])
                ->setSuppliesCost($unitTypeInfo['suppliesCost'])
                ->setBuildTime($unitTypeInfo['buildTime'])
                ->setSpeed($unitTypeInfo['speed'])
                ->setHealth($unitTypeInfo['health'])
                ->setAttack($unitTypeInfo['attack'])
                ->setDefense($unitTypeInfo['defense'])
                ->setDescription($unitTypeInfo['description'])
                ->setGameBuilding($this->getReference($unitTypeInfo['gameBuildingReference']))
            ;

            $manager->persist($unitType);
            $this->addReference($unitTypeInfo['referenceName'], $unitType);
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