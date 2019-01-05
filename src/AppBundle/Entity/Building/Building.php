<?php

namespace AppBundle\Entity\Building;

use AppBundle\Entity\Platform;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductionBuilding
 *
 * @ORM\Table(name="production_buildings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductionBuildingRepository")
 */
class ProductionBuilding extends BaseBuilding
{
    const BUILDING_TYPE = 'production';

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
     * @ORM\Column(name="base_income", type="integer")
     */
    private $baseIncome;

    /**
     * @var Resource
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resource")
     */
    private $resource;

    /**
     * @var BuildingType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Building\BuildingType")
     */
    private $type;


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
     * Set income
     *
     * @param integer $income
     *
     * @return ProductionBuilding
     */
    public function setBaseIncome($baseIncome)
    {
        $this->baseIncome = $baseIncome;

        return $this;
    }

    /**
     * Get income
     *
     * @return int
     */
    public function getBaseIncome()
    {
        return $this->baseIncome;
    }

    /**
     * @return Resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }

    /**
     * @param Resource $resource
     * @return ProductionBuilding
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return BuildingType
     */
    public function getType(): BuildingType
    {
        return $this->type;
    }

    /**
     * @param BuildingType $type
     *
     * @return ProductionBuilding
     */
    public function setType(BuildingType $type)
    {
        $this->type = $type;

        return $this;
    }
}

