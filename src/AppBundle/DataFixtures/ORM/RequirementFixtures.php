<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Requirement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RequirementFixtures extends Fixture implements DependentFixtureInterface
{
    const DEFAULT_REQUIREMENTS = [
        [
            'unitTypeReference' => 'zombieKiller',
            'gameBuildingReference' => 'trainingCamp',
            'level' => 3,
        ],
        [
            'unitTypeReference' => 'guard',
            'gameBuildingReference' => 'trainingCamp',
            'level' => 1,
        ],
        [
            'unitTypeReference' => 'guard',
            'gameBuildingReference' => 'wall',
            'level' => 1,
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::DEFAULT_REQUIREMENTS as $requirementInfo) {
            $requirement = (new Requirement())
                ->setUnitType($this->getReference($requirementInfo['unitTypeReference']))
                ->setGameBuilding($this->getReference($requirementInfo['gameBuildingReference']))
                ->setLevel($requirementInfo['level'])
            ;

            $manager->persist($requirement);
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
            GameBuildingFixtures::class,
            UnitTypeFixtures::class,
        ];
    }
}