<?php

namespace AppBundle\Service\App;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Service\Battle\BattleServiceInterface;
use AppBundle\Service\Utils\CountdownServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class TaskScheduleService implements TaskScheduleServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * @var BattleServiceInterface
     */
    private $battleService;

    /**
     * TaskScheduleService constructor.
     * @param CountdownServiceInterface $countdownService
     */
    public function __construct(CountdownServiceInterface $countdownService,
                                EntityManagerInterface $em,
                                BattleServiceInterface $battleService)
    {
        //$this->countdownService = $countdownService;
        $this->em = $em;
        $this->battleService = $battleService;
    }

    public function processDueTasks(string $entityType): bool
    {
        $dueTasks = $this->getDueTasks($entityType);

        if (0 == count($dueTasks)) {
            return true;
        }

        //TODO - use events if decide to refactor other entities
        switch ($entityType) {
            case ArmyJourney::class:
                $this->battleService->processBattleJourneys($dueTasks);
                break;
        }

        return true;
    }


    private function getDueTasks(string $entityType):array
    {
        $qb = $this->em->createQueryBuilder();
        return $qb->select('t')
            ->from($entityType, 't')
            ->where('t.dueDate <= :now')
            ->setParameter('now', new \DateTime('now'), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()
            ->getResult();
    }
}