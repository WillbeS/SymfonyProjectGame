<?php

namespace AppBundle\Service\ArmyMovement;

use AppBundle\Entity\MilitaryCampaign;
use AppBundle\Entity\Platform;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Entity\Unit;
use AppBundle\Repository\MilitaryCampaignRepository;
use AppBundle\Service\Battle\ArmyServiceInterface;
use AppBundle\Service\Battle\BattleServiceInterface;
use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use AppBundle\Service\Utils\CountdownServiceInterface;
use AppBundle\Service\Utils\GeometryServiceInterface;
use AppBundle\Traits\Findable;
use Doctrine\ORM\EntityManagerInterface;

class MilitaryCampaignService implements MilitaryCampaignServiceInterface
{
    use Findable;

    const SECONDS_PER_GRID_CELL = 60;

    /**
     * @var ArmyServiceInterface
     */
    private $armyService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GeometryServiceInterface
     */
    private $geometryService;

    /**
     * @var MilitaryCampaignRepository
     */
    private $campaignRepository;

    /**
     * @var BattleServiceInterface
     */
    private $battleService;

    /**
     * @var ScheduledTaskServiceInterface
     */
    //private $scheduledTaskService; //do i use this???

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * MilitaryCampaignService constructor.
     * @param ArmyServiceInterface $armyService
     */
    public function __construct(ArmyServiceInterface $armyService,
                                EntityManagerInterface $em,
                                GeometryServiceInterface $geometryService,
                                MilitaryCampaignRepository $militaryCampaignRepository,
                                BattleServiceInterface $battleService,
                                CountdownServiceInterface $countdownService)
    {
        $this->armyService = $armyService;
        $this->em = $em;
        $this->geometryService = $geometryService;
        $this->campaignRepository = $militaryCampaignRepository;
        $this->battleService = $battleService;
        $this->countdownService = $countdownService;
    }


    public function startCampaign(array $requestData,
                                  Platform $origin,
                                  Platform $destination)
    {
        $army = $this->armyService->getArmyFromRequestData($requestData, $origin->getUnits());
        $name = "Attack from {$origin->getUser()->getUsername()} to {$destination->getUser()->getUsername()}";
        $duration = $this->getJourneyDuration($origin, $destination);

        $campaign = $this->createCampaign($name, $army, $origin, $destination);

        $this->setScheduledTask(
            $duration,
            ScheduledTask::ATTACK_JOURNEY,
            $campaign
        );

        $this->em->persist($campaign);
        $this->em->flush();
    }

    public function processCampaign(MilitaryCampaign $campaign)
    {
        $battleReport = $this->battleService->startBattle($campaign);

        //todo fix but when attacker loses but has surviving troops
        if ($battleReport->getWinner() === $battleReport->getAttacker()) {
            $this->startReturnCampaign(
                json_encode($battleReport->getAttackerEndArmy()),
                $campaign
            );
        }

        $this->em->remove($campaign);
        $this->em->flush();
    }

    public function processReturnCampaign(MilitaryCampaign $campaign)
    {
        $troops = json_decode($campaign->getArmy(), true);
        $units = $campaign->getOrigin()->getUnits();
        /** @var Unit $unit */
        foreach ($units as $unit) {
            $count = $troops[$unit->getUnitType()->getName()];
            if (0 === $count) {
                continue;
            }

            $unit->setInBattle($unit->getInBattle() - $count);
            $unit->setIddle($unit->getIddle() + $count);
        }

        $this->em->remove($campaign);
        $this->em->flush();
    }


    private function startReturnCampaign(string $army, MilitaryCampaign $campaign)
    {
        $name = "Return from {$campaign->getDestination()->getName()}";

        $returnCampaign = $this->createCampaign(
            $name,
            $army,
            $campaign->getOrigin(),
            $campaign->getOrigin()
        );

        $this->setScheduledTask(
            $campaign->getDuration(),
            ScheduledTask::RETURN_JOURNEY,
            $returnCampaign
        );

        $this->em->persist($returnCampaign);
        $this->em->flush();
    }

    private function createCampaign(string $name,
                                    string $army,
                                    Platform $origin,
                                    Platform $destination) :MilitaryCampaign
    {



        $campaign = (new MilitaryCampaign())
            ->setName($name)
            ->setArmy($army)
            ->setOrigin($origin)
            ->setDestination($destination)
        ;

        return $campaign;
    }

    private function setScheduledTask(int $duration,
                                     int $taskType,
                                     MilitaryCampaign $campaign)
    {
        $dueDate = $this->countdownService->getEndDate(
            new \DateTime('now'),
            $duration
        );

        $campaign
            ->setStartDate(new \DateTime('now'))
            ->setDuration($duration)
            ->setDueDate($dueDate)
            ->setTaskType($taskType)
        ;

    }

    private function getJourneyDuration(Platform $origin, Platform $destination): int
    {
        $distance =  $this->geometryService->getDistance2d(
            $origin->getGridCell()->getCol(),
            $origin->getGridCell()->getRow(),
            $destination->getGridCell()->getCol(),
            $destination->getGridCell()->getRow()
        );

        return $distance * $this->armyService->getArmySpeed() * self::SECONDS_PER_GRID_CELL; //in seconds
    }
}