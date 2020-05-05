<?php

namespace App\Repository;

use App\Entity\PostCommentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCommentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCommentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCommentType[]    findAll()
 * @method PostCommentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCommentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCommentType::class);
    }

    // /**
    //  * @return PostCommentType[] Returns an array of PostCommentType objects
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
    public function findOneBySomeField($value): ?PostCommentType
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
