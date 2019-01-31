<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//TODO - Delete if not in use
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
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityId;

    /**
     * @var int
     *
     * @ORM\Column(name="taskType", type="integer")
     */
    private $taskType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dueDate", type="datetime")
     */
    private $dueDate;


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
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return ScheduledTask
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
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
}

