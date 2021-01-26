<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\Persistence\ManagerRegistry;


class ReviewRepository extends ServiceEntityRepository {
	public function __construct(ManagerRegistry $registry) {
		parent::__construct($registry, Review::class);
	}

	/**
	 * @return array
	 * @throws Exception
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function getMoviesAverageRating(): array {
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

	/**
	 * @return bool
	 * @throws ConnectionException
	 */
	public function updateMoviesAverageRating(): bool {
		$conn = $this->getEntityManager()->getConnection();
		$sql = '
			UPDATE movie m SET m.rating = :new_rating
				WHERE m.id = :movie_id
		';

		$conn->beginTransaction();
		try {
			$data = $this->getMoviesAverageRating();
			$stmt = $conn->prepare($sql);

			$stmt->bindParam('movie_id', $movie_id);
			$stmt->bindParam('new_rating', $new_rating);

			foreach ($data as $tuple) {
				$movie_id = $tuple['movie_id'];
				$new_rating = $tuple['avg_rating'];
				$stmt->execute();
			}

			$conn->commit();
		} catch (\Doctrine\DBAL\Exception | Exception $e) {
			$conn->rollBack();
			return false;
		}

		return true;
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
