<?php

namespace AppBundle\Entity;


interface ScheduledTaskInterface
{
    public function getId(): int;

    public function getTaskType(): int;

    /**
     * @param int $type
     * @return ScheduledTaskInterface
     */
    public function setTaskType(int $type);

    public function getStartDate(): \DateTime;

    /**
     * @param \DateTime $dateTime
     * @return ScheduledTaskInterface
     */
    public function setStartDate(\DateTime $dateTime);

    public function getDueDate(): \DateTime;

    /**
     * @param \DateTime $dateTime
     * @return ScheduledTaskInterface
     */
    public function setDueDate(\DateTime $dateTime);

    public function getDuration(): int;

    /**
     * @param int $duration
     * @return ScheduledTaskInterface
     */
    public function setDuration(int $duration);

    public function getPlatform(): Platform;
}