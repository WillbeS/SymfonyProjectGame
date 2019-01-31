<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\UserReport;

interface BattleReportServiceInterface
{
    public function getAllByUser(int $userId): array;

    public function getUserReport(int $userId, int $reportId): UserReport;

    public function deleteUserReport(int $userId, int $reportId);
}