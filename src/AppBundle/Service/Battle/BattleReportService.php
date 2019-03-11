<?php

namespace AppBundle\Service\Battle;


use AppBundle\Entity\UserReport;
use AppBundle\Repository\BattleReportRepository;
use AppBundle\Repository\UserReportRepository;
use AppBundle\Traits\AssertFound;
use Doctrine\ORM\EntityManagerInterface;

class BattleReportService implements BattleReportServiceInterface
{
    use AssertFound;
    /**
     * @var UserReportRepository
     */
    private $userReportRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BattleReportService constructor.
     * @param BattleReportRepository $battleReportRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserReportRepository $userReportRepository,
                                EntityManagerInterface $em)
    {
        $this->userReportRepository = $userReportRepository;
        $this->em = $em;
    }

    public function getAllByUser(int $userId): array
    {
        return $this->userReportRepository->findAllByUser($userId);
    }

    public function getUserReport(int $userId, int $reportId): UserReport
    {
        /** @var UserReport $userReport */
        $userReport = $this->userReportRepository->findOneByUserAndReport($userId, $reportId);
        $this->assertFound($userReport);
        $userReport->setIsRead(true);
        $this->em->flush();

        return $userReport;
    }

    public function deleteUserReport(int $userId, int $reportId)
    {
        /** @var UserReport $userReport */
        $userReport = $this->userReportRepository->findOneByUserAndReport($userId, $reportId);
        $this->assertFound($userReport);

        $this->em->remove($userReport);
        $this->em->flush();
    }
}