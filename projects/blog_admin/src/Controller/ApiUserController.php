<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ApiUser;
use App\Form\ApiUserType;
use App\Repository\ApiUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    protected const DEFAULT_LIST_ORDERBY_FIELD = 'id';
    protected const DEFAULT_LIST_ORDERBY_DIRECTION = 'asc';

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
        '/api/user/list',
        name: 'api_users_list_all',
        methods: [ 'GET' ]
    )]
    public function list( Request $request ): Response
    {
        [
            $draw,
            $limit,
            $offset,
            $orderby,
            $direction
        ] = $this->_getListData( $request->query );

        $criteria = [];

        /** @var \App\Entity\ApiUser[] $apiUsers */
        $apiUsers = $this->apiUserRepository->findBy(
            $criteria,
            [ $orderby => $direction ],
            $limit,
            $offset
        );

        $apiUsersTotal = $this->apiUserRepository->count( $criteria );

        return $this->json(
            [
                'data'            =>
                    array_map(
                        fn( $apiUser ) =>
                        [
                            'id'           => $apiUser->getId(),
                            'token'        => $this->_hideToken( $apiUser->getToken() ),
                            'created_date' => $apiUser->getCreatedDate()->format( 'c' ),
                            'status'       => $apiUser->getStatus(),
                        ],
                        $apiUsers
                    ),
                'recordsTotal'    => $apiUsersTotal,
                'recordsFiltered' => $apiUsersTotal,
                'draw'            => $draw,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\InputBag $query
     *
     * @return array
     */
    protected function _getListData( InputBag $query ): array
    {
        $draw      = (int)$query->get( 'draw', 0 );
        $limit     = (int)$query->get( 'length', 10 );
        $offset    = (int)$query->get( 'start', 0 );
        $orderby   = $query->all( 'columns' )[ $query->all( 'order' )[0]['column'] ?? 0 ]['data'] ?? self::DEFAULT_LIST_ORDERBY_FIELD;
        $direction = $query->all( 'order' )[0]['dir'] ?? self::DEFAULT_LIST_ORDERBY_DIRECTION;

        return
        [
            $draw,
            $limit,
            $offset,
            $orderby,
            $direction,
        ];
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function _hideToken( string $token ): string
    {
        return substr( $token, 0, 10 ) . '...' . substr( $token, -10 );
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
    public function add( Request $request, ManagerRegistry $doctrine ): Response
    {
        $apiUser = new ApiUser();
        $form    = $this->createForm( ApiUserType::class, $apiUser );
        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() )
        {
            $apiUser = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist( $apiUser );
            $entityManager->flush();

            $this->addFlash( 'success', 'API User has been successfully added.' );

            return $this->redirectToRoute( 'api_users_list' );
        }

        return $this->render(
            'api_user/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route( '/api/user/edit/{id<[0-9]+>}', name: 'api_users_edit', methods: [ 'GET', 'POST' ] )]
    public function edit( int $id, Request $request, ManagerRegistry $doctrine ): Response
    {
        $apiUser = $this->apiUserRepository->findOneBy( [ 'id' => $id ] );

        if( !$apiUser )
        {
            throw $this->createNotFoundException( 'API User #' . $id . ' not found' );
        }

        $form    = $this->createForm( ApiUserType::class, $apiUser );
        $form->handleRequest( $request );

        if( $form->isSubmitted() && $form->isValid() )
        {
            $apiUser = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist( $apiUser );
            $entityManager->flush();

            $this->addFlash( 'success', 'API User #' . $id . ' has been successfully updated.' );

            return $this->redirectToRoute( 'api_users_list' );
        }

        return $this->render(
            'api_user/edit.html.twig',
            [
                'form'    => $form->createView(),
                'apiUser' => $apiUser,
            ]
        );
    }

    #[Route( '/api/user/delete/{id<[0-9]+>}', name: 'api_users_delete', methods: [ 'DELETE' ] )]
    public function delete( int $id, ManagerRegistry $doctrine ): Response
    {
        $apiUser = $this->apiUserRepository->findOneBy( [ 'id' => $id ] );

        if( !$apiUser )
        {
            return $this->json(
                [
                    'error' => 'API User #' . $id . ' not found'
                ]
            );
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove( $apiUser );
        $entityManager->flush();

        return $this->json(
            [
                'success' => 'API User #' . $id . ' has been successfully deleted.'
            ]
        );
    }
}
