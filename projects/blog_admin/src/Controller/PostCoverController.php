<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCoverController extends AbstractController
{
    #[Route( '/post/cover', name: 'posts_covers_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'post_cover/index.html.twig',
            []
        );
    }
}
