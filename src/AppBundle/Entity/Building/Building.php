<?php

namespace AppBundle\Entity\Building;

use AppBundle\Entity\Platform;
use AppBundle\Entity\PlatformUnitInterface;
use AppBundle\Entity\ScheduledTask;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\TaskScheduleServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Utils\TimerServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Building
 *
 * @ORM\Table(name="buildings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuildingRepository")
 */
class Building implements PlatformUnitInterface
{
    const COST_FACTOR = 1.15;
    const BUILD_TIME_FACTOR = 1.33;

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
     * @var \DateTime
     *
     * @ORM\Column(name="start_build", type="datetime", nullable=true)
     */
    private $startBuild;

    /**
     * @var ScheduledTask
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ScheduledTask")
     */
    private $upgradeTask;

    /**
     * Get id
     *
     * @return int
     */
    public function getId():?int
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

    /**
     * @return ScheduledTask
     */
    public function getUpgradeTask(): ?ScheduledTask
    {
        return $this->upgradeTask;
    }

    /**
     * @param ScheduledTask|null $upgradeTask
     *
     */
    public function setUpgradeTask(ScheduledTask $upgradeTask = null)
    {
        $this->upgradeTask = $upgradeTask;

        return $this;
    }




    /**
     * @return \DateTime
     */
    public function getStartBuild(): ?\DateTime
    {
        return $this->startBuild;
    }

    /**
     * @param \DateTime $startBuild
     *
     * @return Building
     */
    public function setStartBuild(?\DateTime $startBuild)
    {
        $this->startBuild = $startBuild;

        return $this;
    }

    public function getName(): string
    {
        return $this->gameBuilding->getName();
    }

    public function isPending()
    {
        return null !== $this->startBuild;
    }

    /**
     * @return int
     */
    public function getWoodCost(AppServiceInterface $appService): int
    {
        return $appService
            ->getCostPerLevel($this->gameBuilding->getWoodCost(), $this->level);
    }

    /**
     * @return int
     */
    public function getFoodCost(AppServiceInterface $appService): int
    {
        return $appService
            ->getCostPerLevel($this->gameBuilding->getFoodCost(), $this->level);
    }

    /**
     * @return int
     */
    public function getSuppliesCost(AppServiceInterface $appService): int
    {
        return $appService
            ->getCostPerLevel($this->gameBuilding->getSuppliesCost(), $this->level);
    }

    public function getPrice(AppServiceInterface $appService)
    {
        return [
            'Wood' => $this->getWoodCost($appService),
            'Food' => $this->getFoodCost($appService),
            'Supplies' => $this->getSuppliesCost($appService)
        ];
    }

    /**
     * @return int
     */
    public function getBuildTime(AppServiceInterface $appService): int
    {
        return $appService
            ->getBuildTime($this->gameBuilding->getBuildTime(), $this->level);
    }

    /**
     * @return string
     */
    public function getFormatedBuildTime(AppServiceInterface $appService): string
    {
        return $appService->getBuildTimeFormated($this->gameBuilding->getBuildTime(), $this->level);
    }

    public function getRemainingTime(AppServiceInterface $appService): int
    {
        return $appService->getRemainingTime($this->getStartBuild(),
                                            $this->gameBuilding->getBuildTime(),
                                            $this->level);
    }

    public function getRemainingTimeFormated(AppServiceInterface $appService): string
    {
        $remaining = $this->getRemainingTime($appService);

        return $appService->formatTime($remaining);
    }

    public function getSlug()
    {
        return preg_replace(
            '/\s+/',
            '-',
            strtolower(trim($this->getName()))
        );
    }

    public function isPrivate()
    {
        return true;
    }

    public function getOwner()
    {
        return $this->platform->getUser();
    }
}

