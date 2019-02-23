<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\GridCell;
use AppBundle\Entity\Terrain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MapFixtures extends Fixture implements OrderedFixtureInterface
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
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 200;
    }
}