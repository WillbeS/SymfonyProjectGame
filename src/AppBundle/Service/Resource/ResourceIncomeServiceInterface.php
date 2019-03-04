<?php

namespace AppBundle\Service\Resource;


use AppBundle\Entity\GameResource;

interface ResourceIncomeServiceInterface
{
    public function getIncomePerHour(GameResource $resource): int;
}