<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\GameBuilding;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UnitType
 *
 * @ORM\Table(name="unit_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnitTypeRepository")
 */
class UnitType
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="woodCost", type="integer")
     */
    private $woodCost;

    /**
     * @var int
     *
     * @ORM\Column(name="foodCost", type="integer")
     */
    private $foodCost;

    /**
     * @var int
     *
     * @ORM\Column(name="suppliesCost", type="integer")
     */
    private $suppliesCost;

    /**
     * @var int
     *
     * @ORM\Column(name="buildTime", type="integer")
     */
    private $buildTime;

    /**
     * @var int
     *
     * @ORM\Column(name="speed", type="integer")
     */
    private $speed;

    /**
     * @var int
     *
     * @ORM\Column(name="health", type="integer")
     */
    private $health;

    /**
     * @var int
     *
     * @ORM\Column(name="attack", type="integer")
     */
    private $attack;

    /**
     * @var int
     *
     * @ORM\Column(name="defense", type="integer")
     */
    private $defense;

    /**
     * @var GameBuilding
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\GameBuilding")
     */
    private $gameBuilding;

    /**
     * @var ArrayCollection|UnitType[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Requirement", mappedBy="unitType")
     */
    private $requirements;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    public function __construct()
    {
        $this->requirements = new ArrayCollection();
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
     * @return UnitType
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
     * Set woodCost
     *
     * @param integer $woodCost
     *
     * @return UnitType
     */
    public function setWoodCost($woodCost)
    {
        $this->woodCost = $woodCost;

        return $this;
    }

    /**
     * Get woodCost
     *
     * @return int
     */
    public function getWoodCost()
    {
        return $this->woodCost;
    }

    /**
     * Set foodCost
     *
     * @param integer $foodCost
     *
     * @return UnitType
     */
    public function setFoodCost($foodCost)
    {
        $this->foodCost = $foodCost;

        return $this;
    }

    /**
     * Get foodCost
     *
     * @return int
     */
    public function getFoodCost()
    {
        return $this->foodCost;
    }

    /**
     * Set suppliesCost
     *
     * @param integer $suppliesCost
     *
     * @return UnitType
     */
    public function setSuppliesCost($suppliesCost)
    {
        $this->suppliesCost = $suppliesCost;

        return $this;
    }

    /**
     * Get suppliesCost
     *
     * @return int
     */
    public function getSuppliesCost()
    {
        return $this->suppliesCost;
    }

    /**
     * Set buildTime
     *
     * @param integer $buildTime
     *
     * @return UnitType
     */
    public function setBuildTime($buildTime)
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /**
     * Get buildTime
     *
     * @return int
     */
    public function getBuildTime()
    {
        return $this->buildTime;
    }

    /**
     * Set speed
     *
     * @param integer $speed
     *
     * @return UnitType
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set health
     *
     * @param integer $health
     *
     * @return UnitType
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set attack
     *
     * @param integer $attack
     *
     * @return UnitType
     */
    public function setAttack($attack)
    {
        $this->attack = $attack;

        return $this;
    }

    /**
     * Get attack
     *
     * @return int
     */
    public function getAttack()
    {
        return $this->attack;
    }

    /**
     * Set defense
     *
     * @param integer $defense
     *
     * @return UnitType
     */
    public function setDefense($defense)
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * Get defense
     *
     * @return int
     */
    public function getDefense()
    {
        return $this->defense;
    }

    /**
     * @return GameBuilding
     */
    public function getGameBuilding(): GameBuilding
    {
        return $this->gameBuilding;
    }

    /**
     * @param GameBuilding $gameBuilding
     *
     * @return UnitType
     */
    public function setGameBuilding(GameBuilding $gameBuilding)
    {
        $this->gameBuilding = $gameBuilding;

        return $this;
    }

    /**
     * @return UnitType[]|ArrayCollection
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param UnitType[]|ArrayCollection $requirements
     *
     * @return UnitType
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getImageName()
    {
        return preg_replace(
            '/\s+/',
            '-',
            strtolower(trim($this->getName()))
        );
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return UnitType
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }
}

