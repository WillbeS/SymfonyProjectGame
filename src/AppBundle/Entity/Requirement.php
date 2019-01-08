<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\GameBuilding;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Requirement
 *
 * @ORM\Table(name="requirements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RequirementRepository")
 */
class Requirement
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
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var GameBuilding
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\GameBuilding")
     */
    private $gameBuilding;

    /**
     * @var UnitType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UnitType", inversedBy="requirements")
     */
    private $unitType;


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
     * Set level
     *
     * @param integer $level
     *
     * @return Requirement
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
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
     * @return Requirement
     */
    public function setGameBuilding(GameBuilding $gameBuilding)
    {
        $this->gameBuilding = $gameBuilding;

        return $this;
    }

    /**
     * @return UnitType
     */
    public function getUnitType(): UnitType
    {
        return $this->unitType;
    }

    /**
     * @param UnitType $unitType
     *
     * @return Requirement
     */
    public function setUnitType(UnitType $unitType)
    {
        $this->unitType = $unitType;

        return $this;
    }

    public function getBuildingAndLevel()
    {
        return $this->gameBuilding->getName() . ', level ' . $this->level;
    }
}

