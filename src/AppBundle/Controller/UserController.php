<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\Building\BuildingServiceInterface;
use AppBundle\Service\Platform\PlatformServiceInterface;
use AppBundle\Service\Unit\UnitServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
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

}
