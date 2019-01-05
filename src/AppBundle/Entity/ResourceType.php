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
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\Building")
     */
    private $building;

    /**
     * @var int
     *
     * @ORM\Column(name="base_income", type="integer")
     */
    private $baseIncome;

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
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     *
     * @return \AppBundle\Entity\Resource $this
     */
    public function setBuilding(Building $building)
    {
        $this->building = $building;

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

