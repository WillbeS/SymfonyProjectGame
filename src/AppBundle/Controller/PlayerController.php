<?php

namespace AppBundle\Controller;

use AppBundle\Form\ProfileType;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;;
use AppBundle\Service\UserServiceInterface;
use AppBundle\Service\Utils\FileServiceInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class PlayerController extends MainController
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService)
    {
        parent::__construct($gameStateService,
                            $platformService);

        $this->userService = $userService;
    }

    /**
     * @Route("/settlement/{id}/player/profile/{userId}",
     *     name="view_public_profile",
     *     requirements={"id" = "\d+", "userId" = "\d+"})
     */
    public function viewPublicProfile(int $userId)
    {
        $user = $this->userService->getById($userId);

        return $this->render('user/profile.html.twig', [
            'player' => $user,
        ]);
    }

    /**
     * @Route("/settlement/{id}/player/profile/edit",
     *     name="edit_profile",
     *     requirements={"id" = "\d+"})
     */
    public function editProfile(int $id,
                                Request $request,
                                FileServiceInterface $fileService)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->editProfile($user, $fileService);
                return $this->redirectToRoute('view_public_profile', ['id' => $id, 'userId' => $user->getId()]);
            } catch (Exception $e) {
                var_dump('Error! ');
            }
        }

        return $this->render('user/edit-profile.html.twig', [
            'player' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/settlement/{id}/player/all", name="players_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction()
    {
        $players = $this->userService->getAll();

        return $this->render('user/all.html.twig', [
            'currentPage' => 'player',
            'players' => $players,
        ]);
    }
}
