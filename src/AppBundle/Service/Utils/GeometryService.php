<?php

namespace AppBundle\Service\Utils;


class GeometryService implements GeometryServiceInterface
{

    public function getDistance2d(int $x1, int $y1, int $x2, int $y2): float
    {
        $difX = ($x2 - $x1) * ($x2 - $x1);
        $difY = ($y2 - $y1) * ($y2 - $y1);
        $distance = sqrt($difX + $difY);

        return $distance;
    }
}