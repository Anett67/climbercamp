<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findWithSearch(EventSearch $eventSearch){
        $req =  $this->createQueryBuilder('e')
            ->leftJoin('e.eventComments', 'ec')
            ->addSelect('ec')
            ->innerJoin('e.ville', 'v')
            ->addSelect('v')
            ->leftJoin('e.interestedUsers', 'u')
            ->addSelect('u')
            ->innerJoin('e.postedBy', 'pb')
            ->addSelect('pb')
            ->orderBy('e.eventDate', 'ASC');

            if($eventSearch->getTitle()){
                $req = $req->andWhere('e.title LIKE :title')
                            ->setParameter(':title', '%' . $eventSearch->getTitle() . '%'); 
            }

            if($eventSearch->getVille()){
                $req = $req->andWhere('v.nom LIKE :ville')
                            ->setParameter(':ville', '%' . $eventSearch->getVille() . '%'); 
            }

            return $req->getQuery()
                        ->getResult()
        ;
    }

    public function findByVille($ville){

        return $this->createQueryBuilder('e')
            ->leftJoin('e.eventComments', 'ec')
            ->addSelect('ec')
            ->innerJoin('e.ville', 'v')
            ->addSelect('v')
            ->leftJoin('e.interestedUsers', 'u')
            ->addSelect('u')
            ->innerJoin('e.postedBy', 'pb')
            ->addSelect('pb')
            ->andWhere('e.ville = :val')
            ->setParameter('val', $ville)
            ->orderBy('e.eventDate', 'DESC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
