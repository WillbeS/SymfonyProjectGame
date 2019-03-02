<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MilitaryCampaign
 *
 * @ORM\Table(name="military_campaigns")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MilitaryCampaignRepository")
 */
class MilitaryCampaign implements ScheduledTaskInterface
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="army", type="string", length=1000)
     */
    private $army;

    /**
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform")
     */
    private $origin;

    /**
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform")
     */
    private $destination;

    /**
     * @var int
     *
     * @ORM\Column(name="task_type", type="integer")
     */
    private $taskType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due_date", type="datetime")
     */
    private $dueDate;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration; //todo - when finished with all tasks check if this is needed


    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set army
     *
     * @param string $army
     *
     * @return MilitaryCampaign
     */
    public function setArmy($army)
    {
        $this->army = $army;

        return $this;
    }

    /**
     * Get army
     *
     * @return string
     */
    public function getArmy()
    {
        return $this->army;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MilitaryCampaign
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Platform
     */
    public function getOrigin(): Platform
    {
        return $this->origin;
    }

    /**
     * @param Platform $origin
     *
     * @return MilitaryCampaign
     */
    public function setOrigin(Platform $origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return Platform
     */
    public function getDestination(): Platform
    {
        return $this->destination;
    }

    /**
     * @param Platform $destination
     *
     * @return MilitaryCampaign
     */
    public function setDestination(Platform $destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return int
     */
    public function getTaskType(): int
    {
        return $this->taskType;
    }

    /**
     * @param int $taskType
     */
    public function setTaskType(int $taskType)
    {
        $this->taskType = $taskType;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return MilitaryCampaign
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate(): \DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPlatform(): Platform
    {
        return $this->origin;
    }
}

