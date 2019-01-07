<?php

namespace AppBundle\Service\Utils;

interface TimerServiceInterface
{
    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int;

    public function formatTime(int $allSeconds): string;

    public function getTimer(\DateTime $startTime, int $duration): ?string;

//    public function isPending(\DateTime $startTime, int $duration): bool;
}