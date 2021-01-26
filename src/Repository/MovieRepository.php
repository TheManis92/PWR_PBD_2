<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Iterator;
use PhpParser\Builder;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param int $from
     * @param int $to
     * @param array|null $fields
     * @param array|null $sort
     */
    public function findAllEx(int $from=0, $to=0, ?array $fields=null,
                              ?array $sort=null) {
        $qb = $this->createQueryBuilder('m')
            ->setFirstResult($from)
            ->setMaxResults($to + $from);

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function getCount() {
        $qb =  $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $qb;
    }

    public function getMoviesFromPartial($partial, $from=0, $to=0) {
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult(Movie::class, 'm');
		$rsm->addFieldResult('m', 'id', 'id');
		$rsm->addFieldResult('m', 'title', 'title');
		$rsm->addFieldResult('m', 'plot', 'plot');
		$rsm->addFieldResult('m', 'year', 'year');
		$rsm->addFieldResult('m', 'rating', 'rating');
    	$query = $this->getEntityManager()
			->createNativeQuery(
				'SELECT id, title, plot, year, rating FROM movie WHERE title LIKE :partial',
				$rsm
			);
    	$query->setParameter('partial', '%' . $partial . '%');

    	return $query->getResult();
	}



    // /**
    //  * @return Movie[] Returns an array of Movie objects
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
    public function findOneBySomeField($value): ?Movie
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
