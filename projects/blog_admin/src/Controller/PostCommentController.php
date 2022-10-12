<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCommentController extends AbstractController
{
    #[Route( '/post/comment', name: 'posts_comments_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'post_comment/index.html.twig',
            []
        );
    }
}
