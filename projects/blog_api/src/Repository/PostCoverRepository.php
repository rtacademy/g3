<?php

namespace App\Repository;

use App\Entity\PostCover;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostCover>
 *
 * @method PostCover|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCover|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCover[]    findAll()
 * @method PostCover[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCoverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCover::class);
    }

    public function add(PostCover $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PostCover $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
