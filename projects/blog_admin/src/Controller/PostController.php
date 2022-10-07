<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route( '/post', name: 'posts_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'post/index.html.twig',
            []
        );
    }
}
