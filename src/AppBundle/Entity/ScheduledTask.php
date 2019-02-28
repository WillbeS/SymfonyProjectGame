<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduledTask
 *
 * @ORM\Table(name="scheduled_tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScheduledTaskRepository")
 */
class ScheduledTask
{
    const BUILDING_UPGRADE = 1;
    const UNIT_TRAINING = 2;
    const UNIT_TRAVELLING = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="owner_id", type="integer")
     */
    private $ownerId;

    /**
     * @var int
     *
     * @ORM\Column(name="task_type", type="integer")
     */
    private $taskType;

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
    private $duration;

    /**
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform")
     */
    private $platform;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @param int $ownerId
     *
     * @return ScheduledTask
     */
    public function setOwnerId(int $ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }



    /**
     * Set taskType
     *
     * @param integer $taskType
     *
     * @return ScheduledTask
     */
    public function setTaskType($taskType)
    {
        $this->taskType = $taskType;

        return $this;
    }

    /**
     * Get taskType
     *
     * @return int
     */
    public function getTaskType()
    {
        return $this->taskType;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return ScheduledTask
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
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
     *
     * @return ScheduledTask
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;

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
     * @return ScheduledTask
     */
    public function setPlatform(Platform $platform)
    {
        $this->platform = $platform;

        return $this;
    }
}

