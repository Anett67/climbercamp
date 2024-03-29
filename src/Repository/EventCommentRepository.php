<?php

namespace App\Repository;

use App\Entity\EventComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventComment[]    findAll()
 * @method EventComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventComment::class);
    }

    public function findCommentsPagination($event){
        
        return $this->createQueryBuilder('e')
            ->innerJoin('e.postedBy', 'pb')
            ->addSelect('pb')
            ->andWhere('e.event = :val')
            ->setParameter('val', $event)
            ->orderBy('e.postedAt', 'DESC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
    }

    // /**
    //  * @return EventComment[] Returns an array of EventComment objects
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
    public function findOneBySomeField($value): ?EventComment
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
