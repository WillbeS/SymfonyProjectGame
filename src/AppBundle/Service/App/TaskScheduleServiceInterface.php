<?php

namespace AppBundle\Service\App;


use AppBundle\Entity\ScheduledTaskInterface;

interface TaskScheduleServiceInterface
{
    public function processDueTasksByPlatform(int $platformId);

    public function processDueCampaignTasksByPlatform(int $platformId);

    public function processDueTasks(array $dueTasks);
}