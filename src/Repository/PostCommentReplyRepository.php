<?php

namespace App\Repository;

use App\Entity\PostCommentReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCommentReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCommentReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCommentReply[]    findAll()
 * @method PostCommentReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCommentReply::class);
    }

    // /**
    //  * @return PostCommentReply[] Returns an array of PostCommentReply objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostCommentReply
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
