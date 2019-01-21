<?php

namespace AppBundle\Service\Message;

use AppBundle\Entity\Message;
use AppBundle\Entity\MessageTopic;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTopic;
use Doctrine\Common\Collections\Collection;

interface MessageServiceInterface
{
    /**
     * @param int $topicStarterId
     * @return Collection|MessageTopic[]
     */
   // public function getAllTopics(int $currentUserId): array; //TODO - delete if not in use

    /**
     * @param int $userId
     * @return UserTopic[]
     */
    public function getTopicsByUser(int $userId): array;

    //public function getOneTopic(int $userId, int $topicId): MessageTopic; //TODO - delete if not used

//    public function getConvoById(int $convoId): UserTopic;

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