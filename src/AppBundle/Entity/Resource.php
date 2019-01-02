<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResourceRepository")
 */
class Resource
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
     * @var integer
     *
     * @ORM\Column(name="base_income", type="integer", length=11)
     */
    private $baseIncome;

    /**
     * @var BuildingType
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\BuildingType")
     */
    private $buildingType;


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
     * @return \AppBundle\Entity\Resource $this
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
     * @return BuildingType
     */
    public function getBuildingType(): BuildingType
    {
        return $this->buildingType;
    }

    /**
     * @param BuildingType $buildingType
     *
     * @return \AppBundle\Entity\Resource $this
     */
    public function setBuildingType(BuildingType $buildingType)
    {
        $this->buildingType = $buildingType;

        return $this;
    }

    /**
     * @return int
     */
    public function getBaseIncome(): int
    {
        return $this->baseIncome;
    }

    /**
     * @param int $baseIncome
     *
     * @return \AppBundle\Entity\Resource $this
     */
    public function setBaseIncome(int $baseIncome)
    {
        $this->baseIncome = $baseIncome;

        return $this;
    }
}

