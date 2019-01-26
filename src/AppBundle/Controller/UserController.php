<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Form\UserType;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\UserServiceInterface;
use AppBundle\Service\Utils\FileServiceInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends MainController
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
     * @Route("/register", name="user_register_form", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerFormAction()
    {
        $form = $this->createForm(UserType::class);

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/register", name="user_register_process", methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerProcessAction(Request $request,
                                          UserPasswordEncoderInterface $encoder,
                                          PlatformServiceInterface $platformService,
                                          BuildingServiceInterface $buildingService,
                                          UnitServiceInterface $unitService)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->userService
                 ->register($user, $platformService, $encoder, $buildingService, $unitService);

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
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
}
