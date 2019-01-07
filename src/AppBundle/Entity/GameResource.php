<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\Building;
use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResourceRepository")
 */
class GameResource
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
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;

    /**
     * @var ResourceType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ResourceType")
     */
    private $resourceType;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\Building")
     */
    private $building;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime")
     */
    private $updateTime; //todo - delete this when safe


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
     * @return float
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return GameResource
     */
    public function setTotal(float $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return ResourceType
     */
    public function getResourceType(): ?ResourceType
    {
        return $this->resourceType;
    }

    /**
     * @param ResourceType $resourceType
     */
    public function setResourceType(ResourceType $resourceType)
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    /**
     * @return Building
     */
    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    /**
     * @param ?Building $building
     */
    public function setBuilding(Building $building =  null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateTime(): \DateTime
    {
        return $this->updateTime;
    }

    /**
     * @param \DateTime $updateTime
     *
     * @return GameResource
     */
    public function setUpdateTime(\DateTime $updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }
}

