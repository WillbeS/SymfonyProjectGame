<?php

namespace AppBundle\Entity\Building;

use AppBundle\Entity\Platform;
use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table(name="buildings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingRepository")
 */
class Building
{
    const COST_FACTOR = 1.15; //TODO - add as property to gameBuilding

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
     * @var Platform
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Platform", inversedBy="buildings")
     */
    private $platform;



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
     * @return Building
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
     * @return Building
     */
    public function setGameBuilding(GameBuilding $gameBuilding)
    {
        $this->gameBuilding = $gameBuilding;

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
     * @return Building
     */
    public function setPlatform(Platform $platform)
    {
        $this->platform = $platform;

        return $this;
    }

    public function getWoodCost()
    {
        return $this->getCostPerLevel($this->gameBuilding->getWoodCost());
    }

    public function getFoodCost()
    {
        return $this->getCostPerLevel($this->gameBuilding->getFoodCost());
    }

    public function getSuppliesCost()
    {
        return $this->getCostPerLevel($this->gameBuilding->getSuppliesCost());
    }

    //TODO - get this logic out of here
    private function getCostPerLevel(int $baseCost): int
    {
        return floor($baseCost + ($baseCost * $this->level * self::COST_FACTOR));
    }

}

