<?php

namespace App\Repository;

use App\Entity\PostComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostComment>
 *
 * @method PostComment|null find( $id, $lockMode = null, $lockVersion = null )
 * @method PostComment|null findOneBy( array $criteria, array $orderBy = null )
 * @method PostComment[]    findAll()
 * @method PostComment[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class PostCommentRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, PostComment::class );
    }

    public function add( PostComment $entity, bool $flush = false ): void
    {
        $this->getEntityManager()->persist( $entity );

        if( $flush )
        {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( PostComment $entity, bool $flush = false ): void
    {
        $this->getEntityManager()->remove( $entity );

        if( $flush )
        {
            $this->getEntityManager()->flush();
        }
    }
}
