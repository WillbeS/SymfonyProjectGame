<?php

namespace AppBundle\Service\Utils;


interface GeometryServiceInterface
{
    public function getDistance2d(int $x1, int $y1, int $x2, int $y2): float;
}