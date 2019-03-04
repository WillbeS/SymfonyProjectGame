<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\Building;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Platform
 *
 * @ORM\Table(name="platforms")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlatformRepository")
 */
class Platform
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
     * @var GridCell
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GridCell", inversedBy="platform")
     */
    private $gridCell;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="platforms")
     */
    private $user;


    /**
     * @var ArrayCollection|Building[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Building\Building", mappedBy="platform")
     */
    private $buildings;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="res_update_time", type="datetime")
     */
    private $resourceUpdateTime;

    /**
     * @var ArrayCollection|Unit[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Unit", mappedBy="platform")
     */
    private $units;

    /**
     * @var ArrayCollection|GameResource[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GameResource", mappedBy="platform")
     */
    private $resources;



    public function __construct()
    {
        $this->buildings = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->units = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Platform
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
     * @return GridCell
     */
    public function getGridCell(): ?GridCell
    {
        return $this->gridCell;
    }

    /**
     * @param GridCell $grid
     *
     * @return Platform
     */
    public function setGridCell(GridCell $gridCell)
    {
        $this->gridCell = $gridCell;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Platform
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Building[]|ArrayCollection
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param Building
     *
     * @return Platform
     */
    public function addBuilding(Building $building)
    {
        if(!$this->buildings->contains($building)) {
            $building->setPlatform($this);
            $this->buildings->add($building);
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getResourceUpdateTime(): \DateTime
    {
        return $this->resourceUpdateTime;
    }

    /**
     * @param \DateTime $resourceUpdateTime
     *
     * @return Platform
     */
    public function setResourceUpdateTime(\DateTime $resourceUpdateTime)
    {
        $this->resourceUpdateTime = $resourceUpdateTime;

        return $this;
    }

    /**
     * @return Unit[]|ArrayCollection
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param Unit $unit
     * @return Platform
     */
    public function addUnit(Unit $unit)
    {
        if (!$this->units->contains($unit)) {
            $unit->setPlatform($this);
            $this->units->add($unit);
        }

        return $this;
    }

    public function isPrivate()
    {
        return true;
    }

    /**
     * @return GameResource[]|ArrayCollection
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param GameResource[]|ArrayCollection $resources
     */
    public function addResource(GameResource $resource)
    {
        if (!$this->resources->contains($resource)) {
            $resource->setPlatform($this);
            $this->resources->add($resource);
        }

        return $this;
    }
}

