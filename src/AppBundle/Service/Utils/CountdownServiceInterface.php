<?php
/**
 * Created by PhpStorm.
 * User: CeXChester
 * Date: 28/01/2019
 * Time: 20:18
 */

namespace AppBundle\Service\Utils;


interface CountdownServiceInterface
{
    public function getRemainingTime(\DateTime $startDate, int $duration): int;

    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int;

    public function isReady(\DateTime $startDate, int $duration);

    public function getEndDate(\DateTime $startDate, int $duration): \DateTime;
}