<?php

namespace App\DataFixtures;

use App\Document\EmbedMovieRef;
use App\Document\EmbedUserRef;
use App\Document\Review;
use Exception;
use DateTime;

use App\Document\CrewMember;
use App\Document\Genre;
use App\Document\Movie;
use App\Document\User;
use App\Document\Watchlist;
use App\Document\RequestMovie;
use App\Document\EmbeddedMovie;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture {
	// run command
	// php bin/console doctrine:mongodb:fixtures:load
	private const AMOUNT_MOVIES = 20;
	private const AMOUNT_USERS = 20;
	private const AMOUNT_REQUESTS = 20;
	private const AMOUNT_REVIEWS = 1000;

	private const MOVIE_TITLES = [
		'Lucifer', 'Spiderman', 'Avengers',
		'House', 'American Pie', 'Ironman',
		'Little fires everywhere', 'Supernatural',
		'Evil', 'Grey\'s Anatomy', 'Chicago Med',
		'Jak rozpętałem drugą wojnę światową',
		'Boys'
	];

	private const MOVIE_PLOT = <<<'PLOT'
Lucifer Morningstar has decided he's had enough of being 
the dutiful servant in Hell and decides to spend some time 
on Earth to better understand humanity. He settles in Los Angeles
PLOT;

	private const MOVIE_GENRES = [
		'comedy', 'horror', 'tragedy',
		'thriller', 'drama', 'fantasy',
		'sci-fi', 'action', 'adventure',
		'animation', 'bibliography', 'crime',
		'documentary', 'family', 'history',
		'musical', 'mystery', 'sit-com',
		'war', 'western', 'romance'
	];

	private const NAMES = [
		'Michael', 'Tom', 'Mathew',
		'Mike', 'Lindsey', 'Alison',
		'Amber', 'Gregory', 'John',
		'Omar', 'Lisbeth', 'Anne'
	];

	private const SURNAMES = [
		'Ellis', 'Padarecki', 'Bush',
		'German', 'Alejandro', 'Woodside',
		'Brandt', 'Harris', 'Estevez',
		'Garcia', 'Helfer', 'Nguyen'
	];

	private const COUNTRIES = [
		'Poland', 'USA', 'UK',
		'Germany', 'Italy', 'Norway',
		'Russia', 'France', 'Croatia'
	];

	private const LANGUAGES = [
		'polish', 'english', 'spanish',
		'german', 'italian', 'norwegian',
		'russian', 'french', 'croatian'
	];

	private const CREW_FUNCTIONS_PRIORITY = [
		'actor'				=>	1,
		'producer'			=>	2,
		'director'			=>	3,
		'costume designer'	=>	10
	];

	private const USERS = [
		'Avgariat'	=>	[
			'password'	=>	'ImSickOfThis',
			'role'		=>	User::ROLE_ADMINISTRATOR,
			'email'		=>	'fake@waitingfortheend.com'
		],
		'Manis'		=>	[
			'password'	=>	'1234',
			'role'		=>	USER::ROLE_ADMINISTRATOR,
			'email'		=>	'mateuszkogut007@gmail.com'
		]
	];


	private static function getRandomElement($arr) {
		return $arr[array_rand($arr)];
	}

	private static function getRandomElements($arr, $from, $to, $amount=null) {
		if ($amount === null) $amount = mt_rand($from, $to);
		$collection = new ArrayCollection();
		for ($i = 0; $i < $amount; $i++) {
			$collection->add(self::getRandomElement($arr));
		}

		return $collection;
	}

	private static function getRandomMovieTitle() {
		return self::getRandomElement(self::MOVIE_TITLES);
	}

	private static function getRandomGenres($from, $to, $amount=null) {
		return self::getRandomElements(
			self::MOVIE_GENRES, $from, $to, $amount);
	}

	private static function getRandomName() {
		return self::getRandomElement(self::NAMES);
	}

	private static function getRandomSurname() {
		return self::getRandomElement(self::SURNAMES);
	}

	private static function getRandomFullName() {
		return self::getRandomName() . ' ' . self::getRandomSurname();
	}

	private static function getRandomCountries($from, $to, $amount=null) {
		return self::getRandomElements(
			self::COUNTRIES, $from, $to, $amount);
	}

	private static function getRandomLanguage() {
		return self::getRandomElement(self::LANGUAGES);
	}

	private static function getRandomCrewAttributes() {
		$key = array_rand(self::CREW_FUNCTIONS_PRIORITY);
		$attrs = [
			'function'	=>	$key,
			'priority'	=>	self::CREW_FUNCTIONS_PRIORITY[$key]
		];
		$attrs['character'] = $key == 'actor' ? 'Cab driver' : null;

		return $attrs;
	}

	private static function getRandomCast($from, $to, $amount=null) {
		if ($amount === null) $amount = mt_rand($from, $to);
		$collection = new ArrayCollection();
		for ($i = 0; $i < $amount; $i++) {
			$crewAttrs = self::getRandomCrewAttributes();
			$crewMember = new CrewMember($crewAttrs['priority'],
				self::getRandomFullName());
			$crewMember->setFunction($crewAttrs['function'])
				->setCharacter($crewAttrs['character']);
			$collection->add($crewMember);
		}

		return $collection;
	}

	private static function getRandomString() {
		try {
			$str = bin2hex(random_bytes(6));
		}
		catch (Exception $e) {
			$str = null;
		}
		return $str;
	}

	private static function getRandomMovie() {
		$movie = new Movie(self::getRandomMovieTitle());
		$movie->setYear(mt_rand(2000, 2020))
			->setRating(round(mt_rand(1, 100) / 10, 2))
			->setPlot(self::MOVIE_PLOT)
			->setGenres(self::getRandomGenres(1, 3)->toArray())
			->setDirector(self::getRandomFullName())
			->setCountries(self::getRandomCountries(1, 2)->toArray())
			->setLang(self::getRandomLanguage())
			->setCast(self::getRandomCast(3, 20));
		return $movie;
	}

	private static function shouldBeNull() {
		$percent_chance = 5;
		return mt_rand(0, 10000) < $percent_chance * 100;
	}

	private static function getRandomRequestMovie() {
		$movie = self::getRandomMovie();
		$requestMovie = new EmbeddedMovie($movie->getTitle());
		$requestMovie->setYear(
				self::shouldBeNull() ? null : $movie->getYear())
			->setRating(
				self::shouldBeNull() ? null : $movie->getRating())
			->setPlot(
				self::shouldBeNull() ? null : $movie->getPlot())
			->setGenres(
				self::shouldBeNull() ? null : $movie->getGenres())
			->setDirector(
				self::shouldBeNull() ? null : $movie->getDirector())
			->setCountries(
				self::shouldBeNull() ? null : $movie->getCountries())
			->setLang(
				self::shouldBeNull() ? null : $movie->getLang())
			->setCast(
				self::shouldBeNull() ? null : $movie->getCast());
		return $requestMovie;
	}


	public function load(ObjectManager $manager) {
		// load genres
		foreach (self::MOVIE_GENRES as $genreName) {
			$genre = new Genre($genreName);

			$manager->persist($genre);
		}

		// load movies
		$loadedMovies = [];
		for ($i = 0; $i < self::AMOUNT_MOVIES; $i++) {
			$movie = new Movie(self::getRandomMovieTitle() . $i);
			$movie->setYear(mt_rand(2000, 2020))
				->setRating(round(mt_rand(1, 100) / 10, 2))
				->setPlot(self::MOVIE_PLOT)
				->setGenres(self::getRandomGenres(1, 3)->toArray())
				->setDirector(self::getRandomFullName())
				->setCountries(self::getRandomCountries(1, 2)->toArray())
				->setLang(self::getRandomLanguage())
				->setCast(self::getRandomCast(3, 20));

			$manager->persist($movie);
			$loadedMovies[] = $movie;
		}

		// load defined users
		foreach (self::USERS as $userName => $userData) {
			$watchlist = new Watchlist(Watchlist::VISIBILITY_PUBLIC);
			$user = new User($userName, $userData['password'], $userData['role'],
				$userData['email'], $watchlist);
			$user->setName($userName)
				->setPassword($userData['password'])
				->setRole($userData['role'])
				->setEmail($userData['email'])
				->setJoined(new DateTime('now'))
				->setLastVisit(new DateTime('now'))
				->setWatchlist($watchlist);

			$manager->persist($user);
		}

		// load random users
		$loadedUsers = [];
		for ($i = 0; $i < self::AMOUNT_USERS; $i++) {
			$name = self::getRandomString();
			$password = self::getRandomString();
			$email = self::getRandomString() .
				'@' . self::getRandomString() . '.com';
			if ($name === null) continue;

			$watchlist = new Watchlist(Watchlist::VISIBILITY_PUBLIC);
			$user = new User($name, $password, User::ROLE_USER,
				$email, $watchlist);

			$manager->persist($user);
			$loadedUsers[] = $user;
		}

		$manager->flush();

		// load requests
		for ($i = 0; $i < self::AMOUNT_REQUESTS; $i++) {
			$request = new RequestMovie(mt_rand(0, 1000) < 5000 ?
				RequestMovie::ACTION_ADD : RequestMovie::ACTION_EDIT);
			$movie = self::getRandomRequestMovie();
			$loadedMovie = $loadedMovies[array_rand($loadedMovies)];

			$embedMovieRef = new EmbedMovieRef($loadedMovie, $loadedMovie->getTitle());

			$request->setMovie($embedMovieRef)
				->setNewMovie($movie)
				->setCreated(new DateTime('now'))
				->setClosed(null);

			$manager->persist($request);
		}

		// load reviews
		for ($i = 0; $i < self::AMOUNT_REVIEWS; $i++) {
			$loadedUser = $loadedUsers[array_rand($loadedUsers)];
			$loadedMovie = $loadedMovies[array_rand($loadedMovies)];

			$embedMovieRef = new EmbedMovieRef($loadedMovie, $loadedMovie->getTitle());

			$embedUserRef = new EmbedUserRef($loadedUser, $loadedUser->getName());

			$review = new Review(mt_rand(1, 10), $embedUserRef, $embedMovieRef);
			$shallAddComment = mt_rand(0, 10000) > 8000;
			$review->setCreated(new DateTime('now'))
				->setAccepted($shallAddComment ? mt_rand(0, 10000) > 5000 : true)
				->setComment($shallAddComment ? 'What a great movie' : null);

			$manager->persist($review);
		}

		$manager->flush();
	}
}
