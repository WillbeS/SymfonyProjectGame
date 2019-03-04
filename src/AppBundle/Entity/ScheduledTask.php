<?php

namespace AppBundle\Entity;

use AppBundle\Service\ScheduledTask\ScheduledTaskServiceInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * ScheduledTask
 *
 * @ORM\Table(name="scheduled_tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ScheduledTaskRepository")
 */
class ScheduledTask implements ScheduledTaskInterface
{
    const BUILDING_UPGRADE = 1;
    const UNIT_TRAINING = 2;
    const ATTACK_JOURNEY = 3;
    const RETURN_JOURNEY = 4;

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
    private $ownerId; //todo - when finished with all tasks check if this is needed

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
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform")
     */
    private $platform;


    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int|null
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
    public function setTaskType(int $taskType)
    {
        $this->taskType = $taskType;

        return $this;
    }

    /**
     * Get taskType
     *
     * @return int
     */
    public function getTaskType(): int
    {
        return $this->taskType;
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
     * @return ScheduledTask
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     *
     * @return ScheduledTask
     */
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate(): \DateTime
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

