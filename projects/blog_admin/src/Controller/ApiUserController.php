<?php

namespace App\Controller;

use App\Repository\ApiUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    private ApiUserRepository $apiUserRepository;

    public function __construct( ApiUserRepository $apiUserRepository )
    {
        $this->apiUserRepository = $apiUserRepository;
    }

    #[Route( '/api/user', name: 'api_users_list', methods: [ 'GET' ] )]
    public function index(): Response
    {
        return $this->render(
            'api_user/index.html.twig',
            []
        );
    }

    #[Route(
        '/api/user/list/orderby/{orderby<[a-z\_]+>}/direction/{direction<asc|desc>}/offset/{offset<[0-9]+>}',
        name: 'api_users_list_all',
        methods: [ 'GET' ]
    )]
    public function list( string $orderby, string $direction, int $offset ): Response
    {
        /** @var \App\Entity\ApiUser[] $apiUsers */
        $apiUsers = $this->apiUserRepository->findBy(
            [],
            [ $orderby => $direction ],
            10,
            $offset
        );

        return $this->json(
            array_map(
                static fn( $apiUser ) =>
                [
                    'id'    => $apiUser->getId(),
                    'token' => $apiUser->getToken(),
                ],
                $apiUsers
            )
        );
    }
}
