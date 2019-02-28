<?php

namespace AppBundle\Service\App;


interface TaskScheduleServiceInterface
{
    public function processDueTasks(string $type): bool;

    public function processDueTasksByPlatform(int $platformId);
}