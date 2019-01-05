<?php
/**
 * Created by PhpStorm.
 * User: CeXChester
 * Date: 05/01/2019
 * Time: 17:49
 */

namespace AppBundle\Entity\CustomData;


class PlatformData
{
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

    /**
     * @return int
     */
    public function getWoodIncome(): int
    {
        return $this->woodIncome;
    }

    /**
     * @param int $woodIncome
     */
    public function setWoodIncome(int $woodIncome)
    {
        $this->woodIncome = $woodIncome;
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
     */
    public function setFoodIncome(int $foodIncome)
    {
        $this->foodIncome = $foodIncome;
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
     */
    public function setSuppliesIncome(int $suppliesIncome)
    {
        $this->suppliesIncome = $suppliesIncome;
    }
}