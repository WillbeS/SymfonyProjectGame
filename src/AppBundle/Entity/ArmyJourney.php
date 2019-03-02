<?php

namespace AppBundle\Entity;

use AppBundle\Service\Battle\BattleJourneyServiceInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * BattleJourney
 *
 * @ORM\Table(name="army_journeys")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArmyJourneyRepository")
 */
class ArmyJourney
{
    const PURPOSE_BATTLE = 1;

    const PURPOSE_RETURN = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime", nullable=false)
     */
    private $dueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="troops", type="text")
     */
    private $troops;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     *  @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="purpose", type="integer")
     */
    private $purpose;

    /**
     * @var GridCell
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GridCell")
     */
    private $origin;

    /**
     * @var GridCell
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GridCell")
     */
    private $destination;


    /**
     * Get id
     *
     * @return int
     */
    public function getId():?int
    {
        return $this->id;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return ArmyJourney
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     *
     * @return ArmyJourney
     */
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }


    /**
     * Set troops
     *
     * @param string $troops
     *
     * @return ArmyJourney
     */
    public function setTroops($troops)
    {
        $this->troops = $troops;

        return $this;
    }

    /**
     * Get troops
     *
     * @return string
     */
    public function getTroops()
    {
        return $this->troops;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return ArmyJourney
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ArmyJourney
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return GridCell
     */
    public function getOrigin(): GridCell
    {
        return $this->origin;
    }

    /**
     * @param GridCell $origin
     *
     * @return ArmyJourney
     */
    public function setOrigin(GridCell $origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return GridCell
     */
    public function getDestination(): GridCell
    {
        return $this->destination;
    }

    /**
     * @param GridCell $destination
     *
     * @return ArmyJourney
     */
    public function setDestination(GridCell $destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return int
     */
    public function getPurpose(): int
    {
        return $this->purpose;
    }

    /**
     * @param int $purpose
     *
     * @return ArmyJourney
     */
    public function setPurpose(int $purpose)
    {
        $this->purpose = $purpose;

        return $this;
    }


}

