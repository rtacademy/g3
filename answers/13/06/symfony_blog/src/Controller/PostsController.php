<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    #[Route( '/', name: 'homepage', methods: [ 'GET', 'HEAD' ] )]
    public function index( PostRepository $postRepository ): Response
    {
        // Топ запис
        $top_post = $postRepository->getTopPost();

        // Останні N активних записів
        $latest_posts_paginator = $postRepository->getLatestPosts();
//dump($latest_posts_paginator);die;
        return $this->render(
            'posts/index.html.twig',
            [
                'top_post' => $top_post,
                'posts'    => $latest_posts_paginator,
            ]
        );
    }
}