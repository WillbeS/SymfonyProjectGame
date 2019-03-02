<?php

namespace AppBundle\Entity;


interface ScheduledTaskInterface
{
    public function getId(): int;

    public function getTaskType(): int;

    public function getStartDate(): \DateTime;

    public function getDueDate(): \DateTime;

    public function getDuration(): int;

    public function getPlatform(): Platform;
}