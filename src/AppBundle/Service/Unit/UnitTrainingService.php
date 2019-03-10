<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\ScheduledTaskInterface;
use AppBundle\Entity\Unit;
use AppBundle\Repository\UnitRepository;
use AppBundle\Service\App\GameNotificationException;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Traits\Findable;
use Doctrine\ORM\EntityManagerInterface;

class UnitTrainingService implements UnitTrainingServiceInterface
{
    use Findable;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UnitRepository
     */
    private $unitRepository;

    /**
     * UnitTrainingService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,
                                UnitRepository $unitRepository)
    {
        $this->em = $em;
        $this->unitRepository = $unitRepository;
    }


    public function startTraining(int $count,
                                  int $unitId,
                                  PlatformServiceInterface $platformService,
                                  ScheduledTaskServiceInterface $scheduledTaskService)
    {


        $unit = $this->unitRepository->find($unitId);
        $platformService->payPrice($unit->getPlatform(), $unit->getPrice($count));

        $trainingTask = $scheduledTaskService->createPlatformUnitTask(
            ScheduledTask::UNIT_TRAINING,
            $unit->getUnitType()->getBuildTime() * $count,
            $unit
        );

        $unit
            ->setTrainingTask($trainingTask)
            ->addForTraining($count)
        ;

        $this->em->persist($trainingTask);
        $this->em->flush();
    }

    public function finishTraining(ScheduledTaskInterface $trainingTask)
    {
        /**
         * @var Unit $unit
         */
        $unit = $this->unitRepository->findOneBy(['trainingTask' => $trainingTask]);
        $this->assertFound($unit);

        $unit
            ->setTrainingTask(null)
            ->setIddle($unit->getIddle() + $unit->getInTraining())
            ->addForTraining($unit->getInTraining() * -1)
        ;

        $this->em->remove($trainingTask);
        $this->em->flush();
    }
}