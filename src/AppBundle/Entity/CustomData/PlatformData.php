<?php

namespace AppBundle\Entity\CustomData;


use AppBundle\Entity\Platform;

class PlatformData
{
    /**
     * @var Platform
     */
    private $entity;

    /**
     * @var int
     */
    private $woodIncome;

    /**
     * @var int
     */
    private $foodIncome;

    /**
     * @var int
     */
    private $suppliesIncome;

    public function __construct(Platform $platform)
    {
        $this->entity = $platform;
    }

    /**
     * @return int
     */
    public function getWoodIncome(): int
    {
        return $this->woodIncome;
    }

    /**
     * @param int $woodIncome
     *
     * @return PlatformData
     */
    public function setWoodIncome(int $woodIncome)
    {
        $this->woodIncome = $woodIncome;

        return $this;
    }

    /**
     * @return int
     */
    public function getFoodIncome(): int
    {
        return $this->foodIncome;
    }

    /**
     * @param int $foodIncome
     *
     * @return PlatformData
     */
    public function setFoodIncome(int $foodIncome)
    {
        $this->foodIncome = $foodIncome;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuppliesIncome(): int
    {
        return $this->suppliesIncome;
    }

    /**
     * @param int $suppliesIncome
     *
     * @return PlatformData
     */
    public function setSuppliesIncome(int $suppliesIncome)
    {
        $this->suppliesIncome = $suppliesIncome;

        return $this;
    }

    /**
     * @return Platform
     */
    public function getEntity(): Platform
    {
        return $this->entity;
    }

    /**
     * @param Platform $entity
     *
     * @return PlatformData
     */
    public function setEntity(Platform $entity)
    {
        $this->entity = $entity;

        return $this;
    }
}