<?php

namespace App\Repository;

use App\Entity\MovieSubmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieSubmission|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieSubmission|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieSubmission[]    findAll()
 * @method MovieSubmission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieSubmission::class);
    }

    // /**
    //  * @return MovieSubmission[] Returns an array of MovieSubmission objects
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
    public function findOneBySomeField($value): ?MovieSubmission
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
