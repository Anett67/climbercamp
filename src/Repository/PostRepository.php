<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\SearchPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findWithSearch(SearchPost $searchPost){
        $req = $this->createQueryBuilder('p')
            ->innerJoin('p.postedBy', 'pb')
            ->addSelect('pb')
            ->leftJoin('p.postLikes', 'pl')
            ->addSelect('pl')
            ->innerJoin('pc.postedBy', 'pcpb')
            ->addSelect('pc')
            ->orderBy('p.createdAt', 'DESC');

            if($searchPost->getFirstName()){

                $req = $req->andWhere('pb.firstName LIKE :val')
                        ->setParameter(':val', '%' . $searchPost->getFirstName() . '%');
            }

            if($searchPost->getLastName()){

                $req = $req->andWhere('pb.lastName LIKE :lastName')
                        ->setParameter(':lastName', '%' . $searchPost->getLastName() . '%');
            }

            if($searchPost->getKeyword()){

                $req = $req->andWhere('p.body LIKE :keyword')
                        ->setParameter(':keyword', '%' . $searchPost->getKeyword() . '%');
            }



            return $req->getQuery()
                        ->getResult()
        ;
    }

    public function findLocalPosts($ville){

        return $this->createQueryBuilder('p')
            ->innerJoin('p.postedBy', 'pb')
            ->addSelect('pb')
            ->andWhere('pb.ville = :val')
            ->leftJoin('p.postLikes', 'pl')
            ->addSelect('pl')
            ->leftJoin('p.postComments', 'pc')
            ->setParameter('val', $ville)
            ->orderBy('p.createdAt', 'DESC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
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
    public function findOneBySomeField($value): ?Post
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
