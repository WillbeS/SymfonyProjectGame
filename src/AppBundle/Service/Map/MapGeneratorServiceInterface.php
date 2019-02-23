<?php

namespace AppBundle\Service\Map;


interface MapGeneratorServiceInterface
{
    public function generateMap(int $size);

    public function getRandomTerrainReferences(): array;
}