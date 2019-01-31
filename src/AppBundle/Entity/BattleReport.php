<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Battle
 *
 * @ORM\Table(name="battle_reports")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BattleReportRepository")
 */
class BattleReport
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
     * @ORM\Column(name="attacker_start_army", type="text")
     */
    private $attackerStartArmy; //TODO - delete all these if decide to go with rounds

    /**
     * @var string
     *
     * @ORM\Column(name="attacker_end_army", type="text", nullable=true)
     */
    private $attackerEndArmy;

    /**
     * @var string
     *
     * @ORM\Column(name="defender_start_army", type="text", nullable=true)
     */
    private $defenderStartArmy;

    /**
     * @var string
     *
     * @ORM\Column(name="defender_end_army", type="text", nullable=true)
     */
    private $defenderEndArmy;

    /**
     * @var string
     *
     * @ORM\Column(name="rounds", type="text", nullable=true)
     */
    private $rounds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $attacker;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $defender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $winner;


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
     * Set attackerStartArmy
     *
     * @param string $attackerStartArmy
     *
     * @return BattleReport
     */
    public function setAttackerStartArmy($attackerStartArmy)
    {
        $this->attackerStartArmy = $attackerStartArmy;

        return $this;
    }

    /**
     * Get attackerStartArmy
     *
     * @return array
     */
    public function getAttackerStartArmy()
    {
        return json_decode($this->attackerStartArmy, true);
    }

    /**
     * Set attackerEndArmy
     *
     * @param string $attackerEndArmy
     *
     * @return BattleReport
     */
    public function setAttackerEndArmy($attackerEndArmy)
    {
        $this->attackerEndArmy = $attackerEndArmy;

        return $this;
    }

    /**
     * Get attackerEndArmy
     *
     * @return array
     */
    public function getAttackerEndArmy()
    {
        return json_decode($this->attackerEndArmy, true);
    }

    /**
     * Set defenderStartArmy
     *
     * @param string $defenderStartArmy
     *
     * @return BattleReport
     */
    public function setDefenderStartArmy($defenderStartArmy)
    {
        $this->defenderStartArmy = $defenderStartArmy;

        return $this;
    }

    /**
     * Get defenderStartArmy
     *
     * @return array
     */
    public function getDefenderStartArmy()
    {
        return json_decode($this->defenderStartArmy, true);
    }

    /**
     * Set defenderEndArmy
     *
     * @param string $defenderEndArmy
     *
     * @return BattleReport
     */
    public function setDefenderEndArmy($defenderEndArmy)
    {
        $this->defenderEndArmy = $defenderEndArmy;

        return $this;
    }

    /**
     * Get defenderEndArmy
     *
     * @return array
     */
    public function getDefenderEndArmy()
    {
        return json_decode($this->defenderEndArmy, true);
    }

    /**
     * @return User
     */
    public function getAttacker(): User
    {
        return $this->attacker;
    }

    /**
     * @param User $attacker
     *
     * @return BattleReport
     */
    public function setAttacker(User $attacker)
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * @return User
     */
    public function getDefender(): User
    {
        return $this->defender;
    }

    /**
     * @param User $defender
     *
     * @return BattleReport
     */
    public function setDefender(User $defender)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * @return User
     */
    public function getWinner(): User
    {
        return $this->winner;
    }

    /**
     * @param User $winner
     *
     * @return BattleReport
     */
    public function setWinner(User $winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     *
     * @return BattleReport
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getRounds()
    {
        return json_decode($this->rounds, true);
    }

    /**
     * @return string
     */
    public function getRoundsJson(): string
    {
        return $this->rounds;
    }

    /**
     * @param string $rounds
     *
     * @return BattleReport
     */
    public function setRounds(string $rounds)
    {
        $this->rounds = $rounds;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return BattleReport
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }





}

