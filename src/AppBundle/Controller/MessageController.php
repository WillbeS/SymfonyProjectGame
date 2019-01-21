<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use AppBundle\Form\ReplyType;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class MessageController
 * @package AppBundle\Controller
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


    public function __construct(AppServiceInterface $appService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                UserServiceInterface $userService,
                                MessageServiceInterface $messageService)
    {
        parent::__construct($appService, $gameStateService, $platformService, $messageService);

        $this->userService = $userService;
        $this->messageService = $messageService;
    }

    /**
     * @Route("/settlement/{id}/message/new/recipient/{recipientId}", name="send_message",
     *          requirements={"id" = "\d+", "recipientId" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessageAction(int $id, int $recipientId, Request $request)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

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
            'appService' => $this->appService,
            'platform' => $platform,
            'recipient' => $recipient
        ]);
    }

    /**
     * @Route("/settlement/{id}/message/{topicId}/reply", name="send_reply",
     *          requirements={"id" = "\d+", "topicId" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendReplyAction(int $id,
                                  int $topicId,
                                  Request $request)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        $reply = new Message();
        $myConvo = $this->messageService->getOneUserTopic($this->getUser()->getId(),
                                                          $topicId, true,
                                                          MessageController::OLD_REPLIES_COUNT);

        $form = $this->createForm(ReplyType::class, $reply);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $this->messageService->sendReply($reply, $myConvo->getTopic(), $this->getUser());
            $this->addFlash('success', 'Message sent successfully.');

            return $this->redirectToRoute('user_mailbox', ['id' => $id]);
        }

        return $this->render('user/send-reply.html.twig', [
            'form' => $form->createView(),
            'appService' => $this->appService,
            'platform' => $platform,
            'convo' => $myConvo,
        ]);
    }


    /**
     * @Route("/settlement/{id}/mailbox", name="user_mailbox")
     */
    public function mailboxAction(int $id)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        $userTopics = $this->messageService->getTopicsByUser($this->getUser()->getId());

        return $this->render('user/mailbox.html.twig', [
            'userTopics' => $userTopics,
            'platform' => $platform,
            'appService' => $this->appService
        ]);

    }

    /**
     * @Route("/settlement/{id}/mailbox/message/{topicId}", name="read_topic",
     *          requirements={"id" = "\d+", "messageId" = "\d+"})
     */
    public function readTopicAction(int $id, int $topicId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);

        $convo = $this->messageService->readTopic($this->getUser()->getId(), $topicId);
        $this->updateState($platform);

        return $this->render('user/message-read.html.twig', [
            'platform' => $platform,
            'appService' => $this->appService,
            'convo' => $convo,
            'messages' => []
        ]);

    }

    /**
     * @Route("/settlement/{id}/message/{topicId}/delete", name="delete_topic",
     *          requirements={"id" = "\d+", "topicId" = "\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteTopicByUserAction(int $id,
                                    int $topicId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        // Searching for convo by topic and user - to make sure that user's authorized (no need for voter)
        $this->messageService->deleteTopicByUser($this->getUser()->getId(), $topicId);
        $this->addFlash('success', 'Message successfully deleted.');

        return $this->redirectToRoute('user_mailbox', ['id' => $id]);
    }

}
