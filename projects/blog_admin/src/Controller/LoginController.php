<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class LoginController extends AbstractController
{
    private $security;

    public function __construct( Security $security )
    {
        $this->security = $security;
    }

    #[Route( '/', name: 'user_login' )]
    public function index( AuthenticationUtils $authenticationUtils ): Response
    {
        if( $this->security->isGranted( 'ROLE_ADMIN' ) )
        {
            return $this->redirectToRoute( 'dashboard' );
        }

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
