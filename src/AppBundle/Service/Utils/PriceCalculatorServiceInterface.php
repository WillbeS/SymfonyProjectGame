<?php

namespace AppBundle\Service\Utils;


interface PriceCalculatorServiceInterface
{
    public function calculatePriceByLevel(int $basePrice, int $level, int $costFactor): int;

}