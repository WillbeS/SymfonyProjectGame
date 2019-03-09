<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use AppBundle\Form\ReplyType;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package AppBundle\Controller
 *
 * @Route("/settlement/{id}", requirements={"id" = "\d+"})
 */
class MessageController extends MainController
{
    const OLD_REPLIES_COUNT = 3;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var MessageServiceInterface
     */
    private $messageService;


    public function __construct(GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                UserServiceInterface $userService,
                                MessageServiceInterface $messageService)
    {
        parent::__construct($gameStateService,
                            $platformService);

        $this->userService = $userService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/mailbox", name="user_mailbox")
     */
    public function mailboxAction(int $id)
    {
        $userTopics = $this->messageService->getTopicsByUser($this->getUser()->getId());

        return $this->render('user/mailbox.html.twig', [
            'userTopics' => $userTopics,
        ]);

    }

    /**
     * @Route("/message/new/recipient/{recipientId}", name="send_message",
     *          requirements={"recipientId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessageAction(int $id, int $recipientId, Request $request)
    {
        $recipient = $this->userService->getById($recipientId);
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->messageService->sendMessage($recipient, $this->getUser(), $message);
            $this->addFlash('success', 'Message sent successfully.');

            return $this->redirectToRoute('user_mailbox', ['id' => $id]);
        }

        return $this->render('user/send-message.html.twig', [
            'form' => $form->createView(),
            'recipient' => $recipient
        ]);
    }

    /**
     * @Route("/message/{topicId}/reply", name="send_reply",
     *          requirements={"topicId" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendReplyAction(int $id,
                                  int $topicId,
                                  Request $request)
    {
        $reply = new Message();
        $userId = $this->getUser()->getId();
        $myConvo = $this->messageService->getOneUserTopic(
            $userId,
            $topicId,
            true,
            MessageController::OLD_REPLIES_COUNT
        );

        $form = $this->createForm(ReplyType::class, $reply);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $this->messageService->sendReply($reply, $myConvo->getTopic(), $this->getUser());
            $this->addFlash('success', 'Message sent successfully.');

            return $this->redirectToRoute('read_topic', ['id' => $id, 'topicId' => $topicId]);
        }

        return $this->render('user/send-reply.html.twig', [
            'form' => $form->createView(),
            'convo' => $myConvo,
        ]);
    }

    /**
     * @Route("/mailbox/message/{topicId}", name="read_topic",
     *          requirements={"messageId" = "\d+"})
     */
    public function readTopicAction(int $topicId)
    {
        $convo = $this->messageService->readTopic($this->getUser()->getId(), $topicId);

        return $this->render('user/message-read.html.twig', [
            'convo' => $convo,
            //'messages' => []
        ]);

    }

    /**
     * @Route("/message/{topicId}/delete", name="delete_topic",
     *          requirements={"topicId" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTopicByUserAction(int $id, int $topicId)
    {
        $this->messageService->deleteTopicByUser($this->getUser()->getId(), $topicId);
        $this->addFlash('success', 'Message successfully deleted.');

        return $this->redirectToRoute('user_mailbox', ['id' => $id]);
    }

}
