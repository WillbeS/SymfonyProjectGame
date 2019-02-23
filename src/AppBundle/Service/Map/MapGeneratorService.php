<?php

namespace AppBundle\Service\Map;


use AppBundle\Entity\Terrain;

class MapGeneratorService implements MapGeneratorServiceInterface
{
    public function generateMap(int $size)
    {
        $terrainReferences = $this->getRandomTerrainReferences();
        $max = count($terrainReferences) - 1;

        dump($max);
    }

    public function getRandomTerrainReferences(): array
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
}