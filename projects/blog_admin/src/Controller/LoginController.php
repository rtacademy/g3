<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route( '/', name: 'user_login' )]
    public function index( AuthenticationUtils $authenticationUtils ): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last login entered by the user
        $lastLogin = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/index.html.twig',
            [
                'last_login'    => $lastLogin,
                'error'         => $error,
            ]
        );
    }
}
