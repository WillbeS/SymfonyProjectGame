<?php

namespace AppBundle\Entity\Building;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingType
 *
 * @ORM\Table(name="building_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingTypeRepository")
 */
class BuildingType
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
     * @ORM\Column(name="wood_cost", type="integer")
     */
    private $woodCost;

    /**
     * @var int
     *
     * @ORM\Column(name="food_cost", type="integer")
     */
    private $foodCost;

    /**
     * @var int
     *
     * @ORM\Column(name="supplies_cost", type="integer", nullable=true)
     */
    private $suppliesCost;

    /**
     * @var int
     *
     * @ORM\Column(name="build_time", type="integer")
     */
    private $buildTime;


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
     * @return BuildingType
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
     * @return BuildingType
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
     * @return BuildingType
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
     * @return BuildingType
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
     * @return BuildingType
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
}

