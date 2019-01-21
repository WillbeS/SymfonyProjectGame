<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * UserTopic
 *
 * @ORM\Table(name="users_topics",
 * uniqueConstraints={
 *        @UniqueConstraint(name="user_topic",
 *            columns={"user_id", "topic_id"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTopicRepository")
 */
class UserTopic
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
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var MessageTopic
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MessageTopic")
     */
    private $topic;

    /**
     * @var Collection|Message[]
     */
    private $messages;


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
     * @return UserTopic
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
     * @return UserTopic
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return MessageTopic
     */
    public function getTopic(): MessageTopic
    {
        return $this->topic;
    }

    /**
     * @param MessageTopic $topic
     *
     * @return UserTopic
     */
    public function setTopic(MessageTopic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Message[]|Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message[]|Collection $messages
     *
     * @return UserTopic
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param User $current
     * @return User
     */
    public function getPartner(User $current): User
    {
        $sender = $this->topic->getSender();

        return $sender !== $current ? $sender : $this->topic->getRecipient();
    }

    public function getSenderName(User $current)
    {
        $sender = $this->topic->getSender();

        return $sender === $current ? 'me' : $sender->getUsername();
    }

    public function getRecipientName(User $current)
    {
        $recipient = $this->topic->getRecipient();

        return $recipient === $current ? 'me' : $recipient->getUsername();
    }
}

