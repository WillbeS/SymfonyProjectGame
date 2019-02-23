<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Building\GameBuilding;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GameBuildingFixtures extends Fixture
{
    const DEFAULT_GAME_BUILDINGS = [
        [
            'name' => 'Farm',
            'woodCost' => 15,
            'foodCost' => 5,
            'suppliesCost' => 0,
            'buildTime' => 60,
            'description' => 'Can\'t survive without food and this is the place where you can get it. Fortunately your settlers were able to find the perfect spot for it where ground is fertile and harvest is good.',
            'referenceName' => 'farm',
        ],
        [
            'name' => 'Lumber Mill',
            'woodCost' => 5,
            'foodCost' => 15,
            'suppliesCost' => 0,
            'buildTime' => 60,
            'description' => 'Wood is needed for everything. And with civilization gone the forest is thriving with it! It\'s a bit risky going out there for wood with all the hungry zombies walking around but your workers are good at surviving.',
            'referenceName' => 'lumberMill',
        ],
        [
            'name' => 'Factory',
            'woodCost' => 20,
            'foodCost' => 10,
            'suppliesCost' => 0,
            'buildTime' => 300,
            'description' => 'Well, it\'s hardly a real factory (considering there\'s no electricity) but this is the place where your more innovative workers produce supplies like weapons, tools, medical supplies and other stuff.',
            'referenceName' => 'factory',
        ],
        [
            'name' => 'Training Camp',
            'woodCost' => 5,
            'foodCost' => 5,
            'suppliesCost' => 5,
            'buildTime' => 10,
            'description' => 'Your settlement has to be protected from all the dangers this world has to offer. Also, someone needs to go out there to look for more supplies. Use the training camp to prepare your settlers for battle.',
            'referenceName' => 'trainingCamp',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEFAULT_GAME_BUILDINGS as $buildingInfo) {
            $gameBuilding = (new GameBuilding())
                ->setName($buildingInfo['name'])
                ->setWoodCost($buildingInfo['woodCost'])
                ->setFoodCost($buildingInfo['foodCost'])
                ->setSuppliesCost($buildingInfo['suppliesCost'])
                ->setBuildTime($buildingInfo['buildTime'])
                ->setDescription($buildingInfo['description'])
            ;

            $manager->persist($gameBuilding);
            $this->addReference($buildingInfo['referenceName'], $gameBuilding);
        }

        $manager->flush();
    }
}