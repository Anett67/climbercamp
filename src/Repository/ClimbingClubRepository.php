<?php

namespace App\Repository;

use App\Entity\ClimbingClub;
use App\Entity\ClubSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findAllWithPagination(){
        
        return $this->createQueryBuilder('c')
            ->innerJoin('c.ville', 'v')
            ->addSelect('v')
            ->leftJoin('c.climbingCategories', 'cc')
            ->addSelect('cc')
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
    }

    public function findByVille($ville): Query{

        return $this->createQueryBuilder('c')
            ->innerJoin('c.ville', 'v')
            ->addSelect('v')
            ->leftJoin('c.climbingCategories', 'cc')
            ->addSelect('cc')
            ->andWhere('c.ville = :val')
            ->setParameter('val', $ville)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
    }

    public function findSavedClubs($user): Query{
        $clubs = $user->getClimbingClub();

        return $this->createQueryBuilder('c')
            ->innerJoin('c.ville', 'v')
            ->addSelect('v')
            ->leftJoin('c.climbingCategories', 'cc')
            ->addSelect('cc')
            ->andWhere('c IN (:val)')
            ->setParameter('val', $clubs)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
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
