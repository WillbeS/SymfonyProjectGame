<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\Building;
use AppBundle\Service\App\AppServiceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Unit
 *
 * @ORM\Table(name="units")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnitRepository")
 */
class Unit implements PlatformUnitInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Range(
     *     min = 0,
     *     max = 1000,
     *     minMessage = "Quantity cannot be less than {{ limit }}",
     *     maxMessage = "Quantity cannot be greater than {{ limit }}"
     * )
     *
     * @Assert\Type(type="integer")
     *
     *
     * @var int
     *
     * @ORM\Column(name="in_training", type="integer")
     */
    private $inTraining;

    /**
     * @var int
     *
     * @ORM\Column(name="iddle", type="integer")
     */
    private $iddle;

    /**
     * @var int
     *
     * @ORM\Column(name="in_battle", type="integer")
     */
    private $inBattle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_build", type="datetime", nullable=true)
     */
    private $startBuild;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_available", type="boolean")
     */
    private $isAvailable;

    /**
     * @var UnitType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UnitType")
     */
    private $unitType;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\Building")
     */
    private $building;

    /**
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform", inversedBy="units")
     */
    private $platform;

    /**
     * @var ScheduledTask
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ScheduledTask")
     */
    private $trainingTask;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set startBuild
     *
     * @param \DateTime $startBuild
     *
     * @return Unit
     */
    public function setStartBuild($startBuild)
    {
        $this->startBuild = $startBuild;

        return $this;
    }

    /**
     * Get startBuild
     *
     * @return \DateTime
     */
    public function getStartBuild()
    {
        return $this->startBuild;
    }

    /**
     * @return UnitType
     */
    public function getUnitType(): UnitType
    {
        return $this->unitType;
    }

    /**
     * @param UnitType $unitType
     *
     * @return Unit
     */
    public function setUnitType(UnitType $unitType)
    {
        $this->unitType = $unitType;

        return $this;
    }

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     *
     * @return Unit
     */
    public function setBuilding(Building $building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return int
     */
    public function getInTraining(): int
    {
        return $this->inTraining;
    }

    /**
     * @return int
     */
    public function getIddle(): int
    {
        return $this->iddle;
    }

    /**
     * @param int $iddle
     *
     * @return Unit
     */
    public function setIddle(int $iddle)
    {
        $this->iddle = $iddle;

        return $this;
    }

    /**
     * @return int
     */
    public function getInBattle(): int
    {
        return $this->inBattle;
    }

    /**
     * @param int $inBattle
     *
     * @return Unit
     */
    public function setInBattle(int $inBattle)
    {
        $this->inBattle = $inBattle;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    /**
     * @param bool $isAvailable
     *
     * @return Unit
     */
    public function setIsAvailable(bool $isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * @return Platform
     */
    public function getPlatform(): Platform
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     *
     * @return Unit
     */
    public function setPlatform(Platform $platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * @return ScheduledTask
     */
    public function getTrainingTask(): ?ScheduledTask
    {
        return $this->trainingTask;
    }

    /**
     * @param ScheduledTask|null $trainingTask
     */
    public function setTrainingTask(ScheduledTask $trainingTask = null)
    {
        $this->trainingTask = $trainingTask;

        return $this;
    }




    public function getStatus()
    {
        return $this->isAvailable() ? 'available' : 'n/a';
    }

    public function getTotal()
    {
        return $this->inBattle + $this->iddle;
    }

    /**
     *
     * @return Unit
     */
    public function addForTraining(int $newCount)
    {
        $this->inTraining = $this->inTraining + $newCount;

        return $this;
    }

    public function getRemainingTrainingTime(AppServiceInterface $appService): int
    {
        return $appService->getRemainingTrainingTime($this->getStartBuild(),
                                                $this->unitType->getBuildTime(),
                                                $this->inTraining);
    }

    public function getRemainingTimeFormated(AppServiceInterface $appService): string
    {
        $remaining = $this->getRemainingTrainingTime($appService);

        return $appService->formatTime($remaining);
    }

    public function haveInTraining()
    {
        return null !== $this->startBuild;
    }

    public function isPrivate()
    {
        return true;
    }

    public function getOwner()
    {
        return $this->platform->getUser();
    }

    public function getPrice(int $count = 1)
    {
        return [
            'Wood' => $this->getUnitType()->getWoodCost() * $count,
            'Food' => $this->getUnitType()->getFoodCost() * $count,
            'Supplies' => $this->getUnitType()->getSuppliesCost() * $count
        ];
    }
}

