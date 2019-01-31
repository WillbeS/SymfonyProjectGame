<?php

namespace AppBundle\Entity;


interface ScheduledTaskInterface
{
    public function getId(): ?int;

    public function getStartDate(): ?\DateTime;

    public function getDueDate(): ?\DateTime;

    public function getDuration(): ?int;
}