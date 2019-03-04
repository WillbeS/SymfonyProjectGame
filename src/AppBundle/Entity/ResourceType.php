<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Building\GameBuilding;
use Doctrine\ORM\Mapping as ORM;

/**
 * ResourceType
 *
 * @ORM\Table(name="resource_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResourceTypeRepository")
 */
class ResourceType
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
     * @ORM\Column(name="base_income", type="integer")
     */
    private $baseIncome;

    /**
     * @var GameBuilding
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Building\GameBuilding")
     */
    private $gameBuilding;

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
     * @return \AppBundle\Entity\ResourceType $this
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
     * @return int
     */
    public function getBaseIncome(): int
    {
        return $this->baseIncome;
    }

    /**
     * @param int $baseIncome
     *
     * @return \AppBundle\Entity\ResourceType $this
     */
    public function setBaseIncome(int $baseIncome)
    {
        $this->baseIncome = $baseIncome;

        return $this;
    }

    /**
     * @return GameBuilding
     */
    public function getGameBuilding(): ?GameBuilding
    {
        return $this->gameBuilding;
    }

    /**
     * @param GameBuilding $gameBuilding
     *
     * @return ResourceType
     */
    public function setGameBuilding(GameBuilding $gameBuilding)
    {
        $this->gameBuilding = $gameBuilding;

        return $this;
    }

}

