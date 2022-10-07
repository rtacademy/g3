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

        return $this->render(
            'posts/index.html.twig',
            [
                'top_post' => $top_post,
                'posts'    => $latest_posts_paginator,
            ]
        );
    }

    #[Route('/post/{id}-{alias}', name: 'post_view', methods: ['GET', 'HEAD'])]
    public function view( int $id, string $alias, PostRepository $postRepository ): Response
    {
        // Отримання активного запису за ID
        $post = $postRepository->getActivePost( $id );

        if( !$post )
        {
            throw $this->createNotFoundException( 'Post with #' . $id . ' not found' );
        }

        if( $post->getAlias() !== $alias )
        {
            // на випадок некоректного alias у URI -> переходимо до коректної сторінки
            return $this->redirectToRoute(
                'post_view',
                [
                    'id'    => $post->getId(),
                    'alias' => $post->getAlias(),
                ],
                301
            );
        }

        return $this->render(
            'posts/view.html.twig',
            [
                'post' => $post,
            ]
        );
    }
}