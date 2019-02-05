<?php

namespace AppBundle\Service\Battle;

use AppBundle\Entity\ArmyJourney;
use AppBundle\Entity\GridCell;
use AppBundle\Entity\Unit;
use AppBundle\Entity\User;
use AppBundle\Repository\ArmyJourneyRepository;
use AppBundle\Service\App\TaskScheduleServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Utils\CountdownServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JourneyService implements JourneyServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var int
     */
    private $slowestSpeed;

    /**
     * @var CountdownServiceInterface
     */
    private $countdownService;

    /**
     * @var ArmyJourneyRepository
     */
    private $armyJourneyRepository;

    /**
     * @var TaskScheduleServiceInterface
     */
    private $taskScheduleService;

    /**
     * BattleService constructor.
     * @param UnitServiceInterface $unitService
     */
    public function __construct(EntityManagerInterface $em,
                                ArmyJourneyRepository $armyJourneyRepository,
                                TaskScheduleServiceInterface $taskScheduleService,
                                CountdownServiceInterface $countdownService)
    {
        $this->em = $em;
        $this->armyJourneyRepository = $armyJourneyRepository;
        $this->taskScheduleService = $taskScheduleService;
        $this->countdownService = $countdownService;
    }


    public function startJourney(ParameterBag $requestData, User $user, User $target): bool
    {
        $troops = $this->parseRequestData($requestData, $user->getCurrentPlatform()->getUnits());
        $armyJourney = $this->createJourney($user->getCurrentPlatform()->getGridCell(), $target, $troops);
        $this->em->persist($armyJourney);
        dump($armyJourney);
        $this->em->flush();
        return true;
    }

    /**
     * @param GridCell $origin
     * @return ArmyJourney[]
     */
    public function getAllOwnJourneys(GridCell $origin): array
    {
        return $this->armyJourneyRepository->findBy(['origin' => $origin]);
    }

    /**
     * @param GridCell $origin
     * @return ArmyJourney[]
     */
    public function getAllEnemyJourneys(GridCell $destination): array
    {
        return $this->armyJourneyRepository->findBy([
            'destination' => $destination,
            'purpose' => ArmyJourney::PURPOSE_BATTLE
        ]);
    }

    private function createJourney(GridCell $origin, User $target, string $troops)
    {
        $armyJourney = new ArmyJourney();
        $destination = $target->getCurrentPlatform()->getGridCell();
        $distance =  $this->getDistance($origin, $destination);

        if (0 == $distance) {
            throw new \Exception('You cannot attack yourself!');
        }

        $duration = $distance * $this->slowestSpeed * 60; //in seconds
        $startDate = new \DateTime('now');

        $armyJourney
            ->setName("Attack from {$origin->getPlatform()->getUser()->getUsername()} to {$target->getUsername()}")
            ->setOrigin($origin)
            ->setDestination($destination)
            ->setTroops($troops)
            ->setStartDate($startDate)
            ->setDueDate($this->countdownService->getEndDate($startDate, $duration))
            ->setDuration($duration)
            ->setPurpose(ArmyJourney::PURPOSE_BATTLE);

        return $armyJourney;
    }

    private function getDistance(GridCell $coords1, GridCell $coords2)
    {
        $x1 = $coords1->getCol();
        $y1 = $coords1->getRow();
        $x2 = $coords2->getCol();
        $y2 = $coords2->getRow();

        $difX = ($x2 - $x1) * ($x2 - $x1);
        $difY = ($y2 - $y1) * ($y2 - $y1);
        $distance = sqrt($difX + $difY);

        return $distance;
    }

    private function parseRequestData(ParameterBag $requestData, Collection $platformUnits): string
    {
        $attackerArmy = [];
        $slowestSpeed = 0;

        foreach ($requestData as $unitId => $count) {
            if ("" === $unitId || "" === $count || !is_numeric($count)) {
                throw new \Exception("Invalid unit data.");
            }

            $unit = $this->findUnitById($unitId, $platformUnits);
            if (null === $unit) {
                throw new NotFoundHttpException('Unit not found');
            }

            if ($count > $unit->getIddle()) {
                throw new \Exception("Not enough units of type {$unit->getUnitType()->getName()}");
            }

            if ($count > 0 && $slowestSpeed < $unit->getUnitType()->getSpeed()) {
                $slowestSpeed = $unit->getUnitType()->getSpeed();
            }

            $this->updateUnitStatus($count, $unit);
            $attackerArmy[$unit->getUnitType()->getName()] = (int)$count;
        }

        if (0 === $slowestSpeed) {
            throw new \Exception('You must choose at least 1 unit');
        }

        $this->slowestSpeed = $slowestSpeed;
        return json_encode($attackerArmy);
    }

    private function updateUnitStatus(int $count, Unit $unit)
    {
        $unit->setInBattle($unit->getInBattle() + $count);
        $unit->setIddle($unit->getIddle() - $count);
    }

    private function findUnitById(int $id, Collection $units): ?Unit
    {
        foreach ($units as $unit) {
            if ($unit->getId() === $id) {
                return $unit;
            }
        }

        return null;
    }

}