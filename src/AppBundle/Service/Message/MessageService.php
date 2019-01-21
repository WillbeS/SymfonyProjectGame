<?php

namespace AppBundle\Service\Message;


use AppBundle\Entity\Message;
use AppBundle\Entity\MessageTopic;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTopic;
use AppBundle\Repository\MessageRepository;
use AppBundle\Repository\MessageTopicRepository;
use AppBundle\Repository\UserTopicRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageService implements MessageServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var MessageTopicRepository
     */
    private $topicRepository;

    /**
     * @var UserTopicRepository
     */
    private $userTopicRepository;

    public function __construct(EntityManagerInterface $em,
                                MessageRepository $messageRepository,
                                MessageTopicRepository $messageTopicRepository,
                                UserTopicRepository $userTopicRepository)
    {
        $this->em = $em;
        $this->messageRepository = $messageRepository;
        $this->topicRepository = $messageTopicRepository;
        $this->userTopicRepository = $userTopicRepository;
    }

    public function getTopicsByUser(int $userId): array
    {
        return $this->userTopicRepository->findAllByUser($userId);
    }

    /**
     * @param int $topicStarterId
     * @return Collection|Message[]
     */
//    public function getAllTopics(int $currentUserId): array
//    {
//        return $this->topicRepository->findAllByUser($currentUserId);
//    }

    public function getOneUserTopic(int $userId,
                                    int $topicId,
                                    bool $withMessages = false,
                                    int $limit = null): UserTopic
    {
        $userTopic = $this->userTopicRepository->findOneByUserAndTopic($userId, $topicId);
        $this->assertFound($userTopic);

        if ($withMessages) {
            $messages = $this->messageRepository->findAllTopicMessages($topicId, $limit);
            $userTopic->setMessages($messages);
        }

        return $userTopic;
    }

//    public function getConvoById(int $convoId): UserTopic
//    {
//        $convo = $this->userTopicRepository->findOneById($convoId);
//        $this->assertFound($convo);
//
//        return $convo;
//    }

//    public function getOneTopic(int $userId, int $topicId): MessageTopic //todo - make private or delete
//    {
//        return $this->getOneUserTopic($userId, $topicId)->getTopic();
//    }

    public function getNewTopicsCount(int $userId): int
    {
        return $this->userTopicRepository->getNewTopicsCount($userId);
    }

    public function readTopic(int $userId, int $topicId, int $messagesLimit = null): UserTopic
    {
        $userTopic = $this->getOneUserTopic($userId, $topicId, true);

        $userTopic->setIsRead(true);
        $this->em->flush();

        return $userTopic;
    }

    public function sendMessage(User $recipient, User $sender, Message $message)
    {
        $this->createMessageTopic($message, $sender, $recipient);
        $this->createUserTopic($sender, $message->getTopic(), true);
        if ($sender !== $recipient) {
            $this->createUserTopic($recipient, $message->getTopic());
        }

        $message->setSender($sender);

        $this->em->persist($message);
        $this->em->flush();
    }

    public function sendReply(Message $message,
                              MessageTopic $topic,
                              User $currentUser)
    {
        $recipient = $topic->getRecipient() !== $currentUser ? $topic->getRecipient() : $topic->getSender();
        $recipientConvo = $this->getOrCreateUserTopic($recipient, $topic);
        $recipientConvo->setIsRead(false);


        $topic
            ->setUpdatedOn($message->getCreatedOn())
            ->setMessagesCount($topic->getMessagesCount() + 1);
        $message
            ->setTopic($topic)
            ->setSender($currentUser);

        $this->em->persist($message);
        $this->em->flush();
    }

    public function deleteTopicByUser(int $userId, int $topicId)
    {
        $userTopic = $this->getOneUserTopic($userId, $topicId);
        $this->em->remove($userTopic);
        $this->em->flush();
    }

    private function createUserTopic(User $user, MessageTopic $topic, bool $isRead = false)
    {
        $userTopic = new UserTopic();
        $userTopic
            ->setIsRead($isRead)
            ->setTopic($topic)
            ->setUser($user);

        $this->em->persist($userTopic);

        return $userTopic;
    }

    private function createMessageTopic(Message $message, User $sender, User $recipient)
    {
        $topic = $message->getTopic();
        $topic
            ->setMessagesCount(1)
            ->setSender($sender)
            ->setRecipient($recipient)
            ->setUpdatedOn(new \DateTime('now'));

        $this->em->persist($topic);
    }

    //TODO - make it common for all services
    private function assertFound($entity)
    {
        if(!$entity) {

            throw new NotFoundHttpException('Page Not Found');
        }
    }

    private function getOrCreateUserTopic(User $user, MessageTopic $topic): UserTopic
    {
        $userTopic = $this->userTopicRepository->findOneByUserAndTopic($user->getId(), $topic->getId());

        if(null === $userTopic) {
            return $this->createUserTopic($user, $topic);
        }

        return $userTopic;
    }

    ///////////////////////// FOR DELETE ////////////////////////////

//    public function sendMessageBackup(User $recipient, User $sender, Message $message)
//    {
//        $this->createNewTopic($message, $sender, $recipient);
//        $message
//            ->setSender($sender)
//            //->setRecipient($recipient)
//        ;
//
//        $this->em->persist($message);
//        $this->em->flush();
//    }

//    public function readTopicBackup(int $topicId, User $currentUser): MessageTopic
//    {
//        $topic = $this->getTopicById($topicId);
//        $lastSender = $topic->getMessages()[0]->getSender();
//
//        if($lastSender !== $currentUser) {
//            $topic->setIsRead(true);
//        }
//
//        $this->em->flush();
//
//        return $topic;
//    }

//    public function sendReplyBackup(Message $message, MessageTopic $topic, User $sender)
//    {
//        $topic
//            ->setUpdatedOn($message->getCreatedOn())
//            ->setUpdatedBy($sender)
//            ->setIsRead(false);
//        $message
//            ->setTopic($topic)
//            ->setSender($sender);
//
//        $this->em->persist($message);
//        //$this->em->flush();
//    }
}