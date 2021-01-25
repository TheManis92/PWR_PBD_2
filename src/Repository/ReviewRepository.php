<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = NULL, $lockVersion = NULL)
 * @method Review|null findOneBy(array $criteria, array $orderBy = NULL)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL)
 */
class ReviewRepository extends ServiceEntityRepository {
	public function __construct(ManagerRegistry $registry) {
		parent::__construct($registry, Review::class);
	}

	public function getMoviesAverageRating() {
		$conn = $this->getEntityManager()->getConnection();
		$sql = '
			SELECT m.id AS movie_id, AVG(r.rating) AS avg_rating FROM movie m 
				INNER JOIN review r on m.id = r.movie_id 
				WHERE r.is_accepted = 1 
				GROUP BY m.id
		';
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAllAssociative();
	}

	// /**
	//  * @return Review[] Returns an array of Review objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('r')
			->andWhere('r.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('r.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Review
	{
		return $this->createQueryBuilder('r')
			->andWhere('r.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
