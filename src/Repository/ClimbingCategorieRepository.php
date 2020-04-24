<?php

namespace App\Repository;

use App\Entity\ClimbingCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClimbingCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClimbingCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClimbingCategorie[]    findAll()
 * @method ClimbingCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClimbingCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClimbingCategorie::class);
    }

    // /**
    //  * @return ClimbingCategorie[] Returns an array of ClimbingCategorie objects
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
    public function findOneBySomeField($value): ?ClimbingCategorie
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
