<?php

namespace App\Repository;

use App\Entity\ClimbingClub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClimbingClub|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClimbingClub|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClimbingClub[]    findAll()
 * @method ClimbingClub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClimbingClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClimbingClub::class);
    }

    // /**
    //  * @return ClimbingClub[] Returns an array of ClimbingClub objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClimbingClub
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
