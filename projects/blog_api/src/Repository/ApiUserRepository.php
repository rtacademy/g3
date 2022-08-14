<?php

namespace App\Repository;

use App\Entity\ApiUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiUser>
 *
 * @method ApiUser|null find( $id, $lockMode = null, $lockVersion = null )
 * @method ApiUser|null findOneBy( array $criteria, array $orderBy = null )
 * @method ApiUser[]    findAll()
 * @method ApiUser[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class ApiUserRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry )
    {
        parent::__construct( $registry, ApiUser::class );
    }

    public function add( ApiUser $entity, bool $flush = false ): void
    {
        $this->getEntityManager()->persist( $entity );

        if( $flush )
        {
            $this->getEntityManager()->flush();
        }
    }

    public function remove( ApiUser $entity, bool $flush = false ): void
    {
        $this->getEntityManager()->remove( $entity );

        if( $flush )
        {
            $this->getEntityManager()->flush();
        }
    }
}
