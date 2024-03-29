<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\UserSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findWithSearch(UserSearch $userSearch){

        $req = $this->createQueryBuilder('u');

        if($userSearch->getFirstName()){
            $req = $req->andWhere('u.firstName LIKE :firstname')
                        ->setParameter(':firstname', '%' . $userSearch->getFirstName() . '%');
        }

        if($userSearch->getLastName()){
            $req = $req->andWhere('u.lastName LIKE :lastname')
                        ->setParameter(':lastname', '%' . $userSearch->getLastName() . '%');
        }
        
        return $req->getQuery()
                    ->getResult()
        ;

    }

    public function findByVille($ville){

        return $this->createQueryBuilder('u')
            ->innerJoin('u.ville', 'v')
            ->addSelect('v')
            ->andWhere('u.ville = :val')
            ->setParameter('val', $ville)
            ->orderBy('u.lastName', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByVilleWithPagination($ville){

        return $this->createQueryBuilder('u')
            ->innerJoin('u.ville', 'v')
            ->addSelect('v')
            ->andWhere('u.ville = :val')
            ->setParameter('val', $ville)
            ->orderBy('u.lastName', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
    }

    public function findAllWithPagination(){

        return $this->createQueryBuilder('u')
            ->innerJoin('u.ville', 'v')
            ->addSelect('v')
            ->orderBy('u.lastName', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            //->getResult()
        ;
    }

    public function findInterestedUsers(Event $event){

        $users = $event->getInterestedUsers();

    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
