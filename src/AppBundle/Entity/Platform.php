<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\Building;
use AppBundle\Service\App\AppServiceInterface;
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
     * @var GameResource
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", cascade={"persist"})
     */
    private $food;

    /**
     * @var GameResource
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", cascade={"persist"})
     */
    private $wood;

    /**
     * @var GameResource
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GameResource", cascade={"persist"})
     */
    private $supplies;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Building\Building", mappedBy="platform", cascade={"all"})
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



    public function __construct()
    {
        $this->buildings = new ArrayCollection();
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
     * @return GameResource
     */
    public function getFood(): ?GameResource
    {
        return $this->food;
    }

    /**
     * @param int $food
     *
     * @return Platform
     */
    public function setFood(GameResource $food)
    {
        $this->food = $food;

        return $this;
    }

    /**
     * @return Building[]
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @param Building[]|ArrayCollection $buildings
     *
     * @return Platform
     */
    public function addBuilding(Building $building)
    {
        $this->buildings->add($building);

        return $this;
    }

    /**
     * @return GameResource
     */
    public function getWood(): ?GameResource
    {
        return $this->wood;
    }

    /**
     * @param GameResource $wood
     *
     * @return Platform
     */
    public function setWood(GameResource $wood)
    {
        $this->wood = $wood;

        return $this;
    }

    /**
     * @return GameResource
     */
    public function getSupplies(): ?GameResource
    {
        return $this->supplies;
    }

    /**
     * @param GameResource $supplies
     *
     * @return Platform
     */
    public function setSupplies(GameResource $supplies)
    {
        $this->supplies = $supplies;

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



    public function getTotalWood()
    {
        return floor($this->getWood()->getTotal());
    }

    public function getTotalFood()
    {
        return floor($this->getFood()->getTotal());
    }

    public function getTotalSupplies()
    {
        return floor($this->getSupplies()->getTotal());
    }

    /**
     * @return int
     */
    public function getWoodIncome(AppServiceInterface $appService): int
    {
        return $appService->getIncomePerHour($this->wood);
    }

    /**
     * @return int
     */
    public function getFoodIncome(AppServiceInterface $appService): int
    {
        return $appService->getIncomePerHour($this->food);
    }

    /**
     * @return int
     */
    public function getSuppliesIncome(AppServiceInterface $appService): int
    {
        return $appService->getIncomePerHour($this->supplies);
    }

    /**
     * @return Unit[]|ArrayCollection
     */
    public function getUnits()
    {
        return $this->units;
    }

    public function isPrivate()
    {
        return true;
    }
}

