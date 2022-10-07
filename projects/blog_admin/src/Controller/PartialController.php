<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PartialController extends AbstractController
{
    public function menu(): Response
    {
        return $this->render(
            'partial/_menu.html.twig',
            [

            ]
        );
    }
}
