<?php

namespace AppBundle\Entity\ViewData;


class BuildingData
{
    /**
     * @var int
     */
    private $woodCost;

    /**
     * @var int
     */
    private $foodCost;

    /**
     * @var int
     */
    private $suppliesCost;

    /**
     * @var string
     */
    private $buildTime;

    /**
     * @var int
     */
    private $buildTimeSeconds;

    /**
     * @var string
     */
    private $countDown;

    /**
     * @return int
     */
    public function getWoodCost(): int
    {
        return $this->woodCost;
    }

    /**
     * @param int $woodCost
     *
     * @return BuildingData
     */
    public function setWoodCost(int $woodCost)
    {
        $this->woodCost = $woodCost;

        return $this;
    }

    /**
     * @return int
     */
    public function getFoodCost(): int
    {
        return $this->foodCost;
    }

    /**
     * @param int $foodCost
     *
     * @return BuildingData
     */
    public function setFoodCost(int $foodCost)
    {
        $this->foodCost = $foodCost;

        return $this;
    }

    /**
     * @return int
     */
    public function getSuppliesCost(): int
    {
        return $this->suppliesCost;
    }

    /**
     * @param int $suppliesCost
     *
     * @return BuildingData
     */
    public function setSuppliesCost(int $suppliesCost)
    {
        $this->suppliesCost = $suppliesCost;

        return $this;
    }

    /**
     * @return string
     */
    public function getBuildTime(): string
    {
        return $this->buildTime;
    }

    /**
     * @param string $buildTime
     *
     * @return BuildingData
     */
    public function setBuildTime(string $buildTime)
    {
        $this->buildTime = $buildTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getBuildTimeSeconds(): int
    {
        return $this->buildTimeSeconds;
    }

    /**
     * @param int $buildTimeSeconds
     *
     * @return BuildingData
     */
    public function setBuildTimeSeconds(int $buildTimeSeconds)
    {
        $this->buildTimeSeconds = $buildTimeSeconds;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountDown(): string
    {
        return $this->countDown;
    }

    /**
     * @param string $countDown
     *
     * @return BuildingData
     */
    public function setCountDown(string $countDown)
    {
        $this->countDown = $countDown;

        return $this;
    }
}