<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCategoryController extends AbstractController
{
    #[Route( '/post/category', name: 'posts_categories_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'post_category/index.html.twig',
            []
        );
    }
}
