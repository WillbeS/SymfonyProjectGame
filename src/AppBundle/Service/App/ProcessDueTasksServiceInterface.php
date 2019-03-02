<?php

namespace AppBundle\Service\App;


interface ProcessDueTasksServiceInterface
{
    public function processDueTasksByPlatform(int $platformId);

    public function processDueCampaignTasksByPlatform(int $platformId);

    public function processDueTasks(array $dueTasks);
}