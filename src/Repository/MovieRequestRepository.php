<?php

namespace App\Repository;

use App\Entity\MovieRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieRequest[]    findAll()
 * @method MovieRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieRequest::class);
    }

    // /**
    //  * @return MovieRequest[] Returns an array of MovieRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieRequest
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
