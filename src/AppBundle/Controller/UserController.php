<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\User\RegistrationServiceInterface;
use AppBundle\Service\UserServiceInterface;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
                                          RegistrationServiceInterface $registrationService)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $registrationService
                    ->register($user, $encoder);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Username must be unique.');

                return $this->render('user/register.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
            
            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
