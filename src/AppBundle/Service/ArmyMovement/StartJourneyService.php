<?php

namespace AppBundle\Service\ArmyMovement;


use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\Unit;
use AppBundle\Entity\User;
use AppBundle\Service\Utils\CountdownServiceInterface;
use AppBundle\Service\Utils\GeometryServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class StartJourneyService implements StartJourneyServiceInterface
{
    /**
     * @var int
     */
    private $slowestSpeed;

    /**
     * @var GeometryServiceInterface
     */
    private $geometryService;

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * StartJourneyService constructor.
     * @param GeometryServiceInterface $geometryService
     */
    public function __construct(GeometryServiceInterface $geometryService,
                                CountdownServiceInterface $countdownService,
                                EntityManagerInterface $entityManager)
    {
        $this->geometryService = $geometryService;
        $this->countdownService = $countdownService;
        $this->em = $entityManager;
    }

    public function startJourney(array $requestData, User $user, User $target): bool
    {
        $this->slowestSpeed = 0;

        $troops = $this->parseJourneyData($requestData, $user->getCurrentPlatform()->getUnits());
        $armyJourney = $this->createJourney($troops, $user, $target);

        $this->em->persist($armyJourney);
        $this->em->flush();

        return true;
    }

    public function startJourneyHome(string $troops, ArmyJourney $journey)
    {
        $dueDate = $this->countdownService->getEndDate($journey->getDueDate(), $journey->getDuration());
        $journey
            ->setPurpose(ArmyJourney::PURPOSE_RETURN)
            ->setName('Return from ' . $journey->getDestination()->getPlatform()->getName())
            ->setStartDate($journey->getDueDate())
            ->setDueDate($dueDate)
            ->setTroops($troops);
    }

    private function parseJourneyData(array $requestData, Collection $platformUnits): string
    {
        $attackerArmy = [];

        /** @var Unit $unit */
        foreach ($platformUnits as $unit) {
            if (!array_key_exists($unit->getId(), $requestData)) {
                continue;
            }

            $count = $requestData[$unit->getId()];
            $this->setSlowestSpeed($count, $unit->getUnitType()->getSpeed());
            $this->setUnitsToTravel($count, $unit);
            $attackerArmy[$unit->getUnitType()->getName()] = (int)$count;
        }

        if (0 === $this->slowestSpeed) {
            throw new \Exception('You must choose at least 1 unit');
        }

        return json_encode($attackerArmy);
    }

    private function setUnitsToTravel(int $count, Unit $unit)
    {
        if (0 == $count) {
            return;
        }

        $unit->setInBattle($unit->getInBattle() + $count);
        $unit->setIddle($unit->getIddle() - $count);
    }

    private function setSlowestSpeed(int $count, int $speed)
    {
        if (0 == $count || $speed < $this->slowestSpeed) {
            return;
        }

        $this->slowestSpeed = $speed;
    }

    private function createJourney(string $troops, User $currentUser, User $target)
    {
        $armyJourney = new ArmyJourney();
        $origin = $currentUser->getCurrentPlatform()->getGridCell();
        $destination = $target->getCurrentPlatform()->getGridCell();
        $distance =  $this->geometryService->getDistance2d(
                $origin->getCol(),
                $origin->getRow(),
                $destination->getCol(),
                $destination->getRow()
        );

        $duration = $distance * $this->slowestSpeed * 60; //in seconds
        //$duration = 10;

        $armyJourney
            ->setName("Attack from {$origin->getPlatform()->getUser()->getUsername()} to {$target->getUsername()}")
            ->setOrigin($origin)
            ->setDestination($destination)
            ->setTroops($troops)
            ->setStartDate(new \DateTime('now'))
            ->setDueDate($this->countdownService->getEndDate($armyJourney->getStartDate(), $duration))
            ->setDuration($duration)
            ->setPurpose(ArmyJourney::PURPOSE_BATTLE)
        ;

        return $armyJourney;
    }
}