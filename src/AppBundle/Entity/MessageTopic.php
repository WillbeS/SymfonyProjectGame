<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MessageTopic
 *
 * @ORM\Table(name="message_topics")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageTopicRepository")
 */
class MessageTopic
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
     * @ORM\Column(name="about", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Message title must be at least {{ limit }} characters long",
     *     maxMessage = "Message title cannot be longer than {{ limit }} characters"
     * )
     *
     */
    private $about;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_on", type="datetime", nullable=false)
     */
    private $updatedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_on", type="datetime", nullable=true)
     */
    private $deletedOn;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $sender;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $recipient;

    /**
     * @var int
     *
     *  @ORM\Column(name="messages_count", type="integer")
     */
    private $messagesCount;


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
     * Set about
     *
     * @param string $about
     *
     * @return MessageTopic
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return MessageTopic
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set deletedOn
     *
     * @param \DateTime $deletedOn
     *
     * @return MessageTopic
     */
    public function setDeletedOn($deletedOn)
    {
        $this->deletedOn = $deletedOn;

        return $this;
    }

    /**
     * Get deletedOn
     *
     * @return \DateTime
     */
    public function getDeletedOn()
    {
        return $this->deletedOn;
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
     * @return MessageTopic
     */
    public function setSender(User $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User
     */
    public function getRecipient(): User
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     * @return MessageTopic
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessagesCount(): int
    {
        return $this->messagesCount;
    }

    /**
     * @param int $messagesCount
     *
     * @return MessageTopic
     */
    public function setMessagesCount(int $messagesCount)
    {
        $this->messagesCount = $messagesCount;

        return $this;
    }

}

