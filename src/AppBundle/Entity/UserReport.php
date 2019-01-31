<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserReport
 *
 * @ORM\Table(name="users_reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserReportRepository")
 */
class UserReport
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
     * @var bool
     *
     * @ORM\Column(name="isRead", type="boolean")
     */
    private $isRead;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var BattleReport
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BattleReport")
     */
    private $report;


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
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return UserReport
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserReport
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return BattleReport
     */
    public function getReport(): BattleReport
    {
        return $this->report;
    }

    /**
     * @param BattleReport $report
     *
     * @return UserReport
     */
    public function setReport(BattleReport $report)
    {
        $this->report = $report;

        return $this;
    }


}

