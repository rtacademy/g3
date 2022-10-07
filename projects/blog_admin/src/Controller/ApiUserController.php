<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    #[Route( '/api/user', name: 'api_users_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'api_user/index.html.twig',
            []
        );
    }
}
