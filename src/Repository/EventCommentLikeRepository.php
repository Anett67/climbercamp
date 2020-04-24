<?php

namespace App\Repository;

use App\Entity\EventCommentLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventCommentLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventCommentLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventCommentLike[]    findAll()
 * @method EventCommentLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCommentLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCommentLike::class);
    }

    // /**
    //  * @return EventCommentLike[] Returns an array of EventCommentLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventCommentLike
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
