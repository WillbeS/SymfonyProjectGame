<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\GridCell;
use AppBundle\Entity\Terrain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MapFixtures extends Fixture implements DependentFixtureInterface
{
    const MAP_SIZE = 50;

    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $terrainReferences = $this->getRandomTerrainReferences();
        $max = count($terrainReferences) - 1;
        $size = self::MAP_SIZE;

        for ($row = 1; $row <= $size; $row++) {
            for ($col = 1; $col <= $size; $col++) {
                $terrainReference = $terrainReferences[rand(0, $max)];
                $grid = new GridCell();
                $grid
                    ->setRow($row)
                    ->setCol($col)
                    ->setTerrain($this->getReference($terrainReference));
                $manager->persist($grid);
            }
        }

        $manager->flush();
    }


    private function getRandomTerrainReferences(): array
    {
        $terrainTypes = Terrain::TERRAIN_TYPES;
        $terrainReferences = [];

        /**
         * @var $terrainType Terrain
         */

        foreach ($terrainTypes as $name => $randomFactor) {
            for ($i = 0; $i < $randomFactor; $i++) {
                $terrainReferences[] = $name;
            }
        }

        shuffle($terrainReferences);

        return $terrainReferences;
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
          TerrainFixtures::class
        ];
    }
}