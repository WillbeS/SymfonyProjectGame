<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\App\AppServiceInterface;
use AppBundle\Service\App\GameStateServiceInterface;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\Unit\UnitTypeServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
                                PlatformServiceInterface $platformService)
    {
        parent::__construct($appService, $gameStateService, $platformService); //TODO clean up when safe
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

            if($user) {
                $unitService->createAllTypes($user->getCurrentPlatform(), $buildingService);
            }

            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/settlement/{platformId}/player/all", name="players_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction(int $platformId)
    {
        $platform = $this->getPlatform($platformId);
        $players = $this->userService->getAll();

        return $this->render('user/all.html.twig', [
            'currentPage' => 'player',
            'players' => $players,
            'platform' => $platform,
            'appService' => $this->appService
        ]);
    }

    /**
     * @Route("/settlement/{platformId}/player/profile/{userId}", name="view_public_profile")
     *
     */
    public function viewPublicProfile(int $platformId, int $userId)
    {
        $platform = $this->getPlatform($platformId);
        $user = $this->userService->getById($userId);
        return $this->render('user/profile.html.twig', [
            'player' => $user,
            'platform' => $platform,
            'appService' => $this->appService,
        ]);
    }
}
