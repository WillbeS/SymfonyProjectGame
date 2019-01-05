<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grid
 *
 * @ORM\Table(name="grids")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GridRepository")
 */
class Grid
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
     * @ORM\Column(name="row", type="integer", unique=false)
     */
    private $row;

    /**
     * @var int
     *
     * @ORM\Column(name="col", type="integer", unique=false)
     */
    private $col;

    /**
     * @var Terrain
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Terrain")
     */
    private $terrain;

    /**
     * @var Platform
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Platform", mappedBy="gridCell")
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
     * Set row
     *
     * @param integer $row
     *
     * @return Grid
     */
    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get row
     *
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * Set col
     *
     * @param integer $col
     *
     * @return Grid
     */
    public function setCol($col)
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get col
     *
     * @return int
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @return Terrain
     */
    public function getTerrain(): ?Terrain
    {
        return $this->terrain;
    }

    /**
     * @param Terrain $terrain
     *
     * @return Grid
     */
    public function setTerrain(Terrain $terrain)
    {
        $this->terrain = $terrain;

        return $this;
    }

    /**
     * @return Platform
     */
    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    /**
     * @param Platform $platform
     *
     * @return Grid
     */
    public function setPlatform(Platform $platform)
    {
        $this->platform = $platform;

        return $this;
    }

}

