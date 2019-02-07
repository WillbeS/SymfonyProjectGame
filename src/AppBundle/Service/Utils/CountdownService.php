<?php

namespace AppBundle\Service\Utils;

//TODO - make this the only one!!!
class CountdownService implements CountdownServiceInterface
{
    public function getRemainingTime(\DateTime $startDate, int $duration): int
    {
        $elapsed = $this->getElapsedTime($startDate);

        return $duration - $elapsed;
    }

    public function getElapsedTime(\DateTime $date1, \DateTime $date2 = null): int
    {
        if(null == $date2) {
            $date2 = new \DateTime('now');
        }

        return $date2->getTimestamp() - $date1->getTimestamp();
    }

    public function isReady(\DateTime $startDate, int $duration)
    {
        return $this->getRemainingTime($startDate, $duration) <= 0;
    }

    public function getEndDate(\DateTime $startDate, int $duration): \DateTime
    {
        $endTimestamp = $startDate->getTimestamp() + $duration;
        $date = new \DateTime();
        $date->setTimestamp($endTimestamp);

        return $date;
    }
}