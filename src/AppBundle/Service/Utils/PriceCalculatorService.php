<?php

namespace AppBundle\Service\Utils;


class PriceCalculatorService implements PriceCalculatorServiceInterface
{

    public function calculatePriceByLevel(int $basePrice, int $level, int $costFactor): int
    {
        return floor($basePrice + ($basePrice * $level * $costFactor));
    }
}