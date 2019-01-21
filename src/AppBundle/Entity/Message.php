<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 1000,
     *     minMessage = "Message title must be at least {{ limit }} characters long",
     *     maxMessage = "Message title cannot be longer than {{ limit }} characters"
     * )
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $sender;

    /**
     * @var MessageTopic
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MessageTopic")
     *
     * @Assert\Valid()
     */
    private $topic;


    public function __construct()
    {
        $this->createdOn = new \DateTime('now');
    }


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
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     *
     * @return Message
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;

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
     * @return Message
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return MessageTopic
     */
    public function getTopic(): ?MessageTopic
    {
        return $this->topic;
    }

    /**
     * @param MessageTopic $topic
     *
     * @return Message
     */
    public function setTopic(MessageTopic $topic)
    {
        $this->topic = $topic;

        return $this;
    }
}

