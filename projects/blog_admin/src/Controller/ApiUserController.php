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

    #[Route( '/api/user/view/{id<[0-9]+>}', name: 'api_users_view', methods: [ 'GET' ] )]
    public function view( int $id ): Response
    {
        $apiUser = $this->apiUserRepository->findOneBy( [ 'id' => $id ] );

        if( !$apiUser )
        {
            throw $this->createNotFoundException( 'API User #' . $id . ' not found' );
        }

        return $this->render(
            'api_user/view.html.twig',
            [
                'apiUser' => $apiUser,
            ]
        );
    }

    #[Route( '/api/user/add', name: 'api_users_add', methods: [ 'GET', 'POST' ] )]
    public function add(): Response
    {
// TODO
//        $apiUser = new ApiUser();
//        $apiUser->setName($request->request->get('name'));
//        $apiUser->setDescription($request->request->get('description'));
//        $this->apiUserRepository->persist($apiUser);
//        $this->apiUserRepository->flush();

        return $this->render(
            'api_user/add.html.twig',
            [
            ]
        );
    }

    #[Route( '/api/user/edit/{id<[0-9]+>}', name: 'api_users_edit', methods: [ 'GET', 'POST' ] )]
    public function edit( int $id ): Response
    {
        $apiUser = $this->apiUserRepository->findOneBy( [ 'id' => $id ] );

        if( !$apiUser )
        {
            throw $this->createNotFoundException( 'API User #' . $id . ' not found' );
        }

// TODO
//        $apiUser->setName($content->name);
//        $apiUser->setDescription($content->description);
//        $this->apiUserRepository->flush();

        return $this->render(
            'api_user/edit.html.twig',
            [
            ]
        );
    }

    #[Route( '/api/user/delete/{id<[0-9]+>}', name: 'api_users_delete', methods: [ 'DELETE' ] )]
    public function delete( int $id ): Response
    {
        $apiUser = $this->apiUserRepository->findOneBy( [ 'id' => $id ] );

        if( !$apiUser )
        {
            throw $this->createNotFoundException( 'API User #' . $id . ' not found' );
        }

// TODO
//        $this->apiUserRepository->remove($apiUser);
//        $this->apiUserRepository->flush();
    }
}
