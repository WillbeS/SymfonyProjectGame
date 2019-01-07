<?php

namespace AppBundle\Service\Utils;



use Doctrine\ORM\EntityManagerInterface;

class TimerService implements TimerServiceInterface
{
    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int
    {
        if(null == $date2) {
            $date2 = new \DateTime('now');
        }

        return $date2->getTimestamp() - $date1->getTimestamp();
    }

    public function formatTime(int $allSeconds): string
    {
        $seconds = $this->padZeroes($allSeconds % 60);
        $minutes = $this->padZeroes(floor($allSeconds / 60) % 60);
        $hours = $this->padZeroes(floor($allSeconds / 3600));


        return "$hours:$minutes:$seconds";
    }

    private function padZeroes($num): string
    {
        return str_pad($num, 2, '0', STR_PAD_LEFT);
    }

//    public function isPending(\DateTime $startTime, int $duration): bool
//    {
//        $elapsed = $this->getElapsedTime($startTime);
//
//        return $elapsed < $duration;
//    }

    public function getTimer(\DateTime $startTime, int $duration): ?string
    {
        $elapsed = $this->getElapsedTime($startTime);
        $remaining = $duration - $elapsed;

        if($remaining >= 0) {
            return $this->formatTime($remaining);
        } else {
            return null;
        }
    }
}