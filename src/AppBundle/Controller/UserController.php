<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Platform;
use AppBundle\Entity\User;
use AppBundle\Form\ProfileType;
use AppBundle\Form\UserType;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Message\MessageServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\UserServiceInterface;
use AppBundle\Service\Utils\FileServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
                                AppServiceInterface $appService,
                                GameStateServiceInterface $gameStateService,
                                PlatformServiceInterface $platformService,
                                MessageServiceInterface $messageService)
    {
        parent::__construct($appService, $gameStateService, $platformService, $messageService); //TODO clean up when safe
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
            $user = $this->userService
                ->register($user, $platformService, $encoder, $buildingService, $unitService);

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/settlement/{id}/player/profile/{userId}", name="view_public_profile")
     *
     */
    public function viewPublicProfile(int $id, int $userId)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $user = $this->userService->getById($userId);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->updateState($platform);

        return $this->render('user/profile.html.twig', [
            'player' => $user,
            'platform' => $platform,
            'appService' => $this->appService,
        ]);
    }

    /**
     * @Route("/settlement/{id}/player/profile/{userId}/edit", name="edit_profile",
     *     requirements={"id" = "\d+", "userId" = "\d+"})
     *
     */
    public function editProfile(int $id, int $userId, Request $request, FileServiceInterface $fileService)
    {
        $platform = $this->platformService->getOneJoinedAll($id);
        $user = $this->userService->getById($userId);
        $this->denyAccessUnlessGranted('view', $platform);
        $this->denyAccessUnlessGranted('edit', $user);
        $this->updateState($platform);

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userService->editProfile($user, $fileService);
                return $this->redirectToRoute('view_public_profile', ['id' => $id, 'userId' => $userId]);
            } catch (Exception $e) {
                var_dump('Error! ');
            }

        }

        return $this->render('user/edit-profile.html.twig', [
            'player' => $user,
            'platform' => $platform,
            'appService' => $this->appService,
            'form' => $form->createView()
        ]);
    }
}
