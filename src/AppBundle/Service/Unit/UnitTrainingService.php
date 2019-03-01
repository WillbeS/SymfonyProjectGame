<?php

namespace AppBundle\Service\Unit;


use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\Unit;
use AppBundle\Repository\UnitRepository;
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
                                  Unit $unit,
                                  PlatformServiceInterface $platformService,
                                  ScheduledTaskServiceInterface $scheduledTaskService): bool
    {
        if($count <= 0 || $count > PHP_INT_MAX) {
            return false;
        }

        $platformService->payPrice($unit->getPlatform(), $unit->getPrice($count));

        $trainingTask = $scheduledTaskService->createTask(
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

        return true;
    }

    public function finishTraining(ScheduledTask $trainingTask)
    {
        /**
         * @var Unit $unit
         */
        $unit = $this->unitRepository->find($trainingTask->getOwnerId());
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