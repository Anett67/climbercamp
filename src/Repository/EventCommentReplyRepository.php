<?php

namespace App\Repository;

use App\Entity\EventCommentReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventCommentReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventCommentReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventCommentReply[]    findAll()
 * @method EventCommentReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCommentReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCommentReply::class);
    }

    // /**
    //  * @return EventCommentReply[] Returns an array of EventCommentReply objects
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
    public function findOneBySomeField($value): ?EventCommentReply
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
