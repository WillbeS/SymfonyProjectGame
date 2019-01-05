<?php

namespace AppBundle\Service\Map;


use AppBundle\Entity\GridCell;

interface MapServiceInterface
{
    public function findAllByDistrict(int $district): array;

    public function findAvailableGridCell() : ?GridCell;

    public function findAvailableByDistrict(int $district): array;
}