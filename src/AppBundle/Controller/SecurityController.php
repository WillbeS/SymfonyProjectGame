<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if (null !== $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     * @return \Exception
     */
    public function logout()
    {
        throw new \Exception('Logout failed!');
    }
}
