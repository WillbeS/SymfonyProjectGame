<?php

namespace AppBundle\Service\Message;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageTopic;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTopic;


interface MessageServiceInterface
{
    /**
     * @param int $userId
     * @return UserTopic[]
     */
    public function getTopicsByUser(int $userId): array;


    public function getOneUserTopic(int $userId,
                                    int $topicId,
                                    bool $withMessages = false,
                                    int $limit = null): UserTopic;

    public function readTopic(int $userId, int $topicId, int $messagesLimit = null): UserTopic;

    public function sendMessage(User $recipient, User $sender, Message $message);

    public function sendReply(Message $message,
                              MessageTopic $topic,
                              User $currentUser);

    public function getNewTopicsCount(int $userId): int;

    public function deleteTopicByUser(int $userId, int $topicId);
}