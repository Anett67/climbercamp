<?php

namespace App\Repository;

use App\Entity\ClimbingClub;
use App\Entity\ClubSearch;
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

    public function findWithSearch(ClubSearch $clubSearch){

        $req = $this->createQueryBuilder('c')
            ->innerJoin('c.ville', 'v')
            ->addSelect('v')
            ->leftJoin('c.climbingCategories', 'cc')
            ->addSelect('cc')
            ->orderBy('c.id', 'ASC')
        ;

        if($clubSearch->getName()){
            $req = $req->andWhere('c.nom LIKE :nom')
            ->setParameter(':nom', '%' . $clubSearch->getName() . '%');
        }

        if($clubSearch->getVille()){
            $req = $req->andWhere('v.nom LIKE :ville')
            ->setParameter(':ville', '%' . $clubSearch->getVille() . '%');
        }

        return $req->getQuery()
                    ->getResult()
    ;

    }

    public function findByVille($ville){

        return $this->createQueryBuilder('c')
            ->innerJoin('c.ville', 'v')
            ->addSelect('v')
            ->leftJoin('c.climbingCategories', 'cc')
            ->addSelect('cc')
            ->andWhere('c.ville = :val')
            ->setParameter('val', $ville)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
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
