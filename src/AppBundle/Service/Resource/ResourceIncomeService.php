<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\GameResource;

class ResourceIncomeService implements ResourceIncomeServiceInterface
{
    const INCOME_FACTOR = .34;

    public function getIncomePerHour(GameResource $resource): int
    {
        if(null == $resource->getBuilding()) {
            return $resource->getResourceType()->getBaseIncome();
        }

        $baseIncome = $resource->getResourceType()->getBaseIncome();
        $buildingLevel = $resource->getBuilding()->getLevel();
        $income = round($baseIncome + $baseIncome * $buildingLevel * self::INCOME_FACTOR);

        return $income;
    }
}