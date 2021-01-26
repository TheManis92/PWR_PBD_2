<?php

namespace App\DataFixtures;

use App\DBAL\EnumMovieRequestAction;
use App\Entity\Country;
use App\Entity\Genre;
use App\Entity\Lang;
use App\Entity\Movie;
use App\Entity\MovieRequest;
use App\Entity\MovieSubmission;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture {
	// run command
	// php bin/console doctrine:fixtures:load
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

	/*
	private const CREW_FUNCTIONS_PRIORITY = [
		'actor'				=>	1,
		'producer'			=>	2,
		'director'			=>	3,
		'costume designer'	=>	10
	];
	*/

	private const USERS = [
		'admin'	=>	[
			'password'	=>	'admin',
			'role'		=>	Role::ROLE_ADMINISTRATOR,
			'email'		=>	'admin@admin.com'
		],
		'user'		=>	[
			'password'	=>	'user',
			'role'		=>	Role::ROLE_USER,
			'email'		=>	'user@user.com'
		],
		'moderator'		=>	[
			'password'	=>	'moderator',
			'role'		=>	Role::ROLE_MODERATOR,
			'email'		=>	'moderator@moderator.com'
		],
	];
	private array $genres;
	private array $langs;
	private array $countries;
	private array $cast;


	private static function getRandomElement($arr) {
		return $arr[array_rand($arr)];
	}

	private static function getRandomElements($arr, $from, $to,
											  $amount=null): ArrayCollection {
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

	private function getRandomGenres($from, $to, $amount=null): ArrayCollection {
		return self::getRandomElements(
			$this->genres, $from, $to, $amount);
	}

	private static function getRandomName() {
		return self::getRandomElement(self::NAMES);
	}

	private static function getRandomSurname() {
		return self::getRandomElement(self::SURNAMES);
	}

	private function getRandomCountries($from, $to, $amount=null): ArrayCollection {
		return self::getRandomElements(
			$this->countries, $from, $to, $amount);
	}

	private function getRandomLangs($from, $to, $amount=null): ArrayCollection {
		return self::getRandomElements(
			$this->langs, $from, $to, $amount);
	}

	/*
	private static function getRandomCrewAttributes() {
		$key = array_rand(self::CREW_FUNCTIONS_PRIORITY);
		$attrs = [
			'function'	=>	$key,
			'priority'	=>	self::CREW_FUNCTIONS_PRIORITY[$key]
		];
		$attrs['character'] = $key == 'actor' ? 'Cab driver' : null;

		return $attrs;
	}
	*/

	private function getRandomCast($from, $to, $amount=null): ArrayCollection {
		return self::getRandomElements(
			$this->cast, $from, $to, $amount);
	}

	private static function getRandomString(): ?string {
		try {
			$str = bin2hex(random_bytes(6));
		}
		catch (Exception $e) {
			$str = null;
		}
		return $str;
	}

	private function getRandomMovie(): Movie {
		$movie = new Movie();
		$movie->setTitle(self::getRandomMovieTitle())
			->setYear(mt_rand(2000, 2020))
			->setPlot(self::MOVIE_PLOT)
			->addGenres($this->getRandomGenres(1, 3)->toArray())
			->addCountries($this->getRandomCountries(1, 2)->toArray())
			->addLangs($this->getRandomLangs(1, 2)->toArray())
			->addCastBulk($this->getRandomCast(3, 20)->toArray());
		return $movie;
	}

	private static function shouldBeNull(): bool {
		$percent_chance = 5;
		return mt_rand(0, 10000) < $percent_chance * 100;
	}

	private function getRandomMovieSubmission(): MovieSubmission {
		$movie = $this->getRandomMovie();
		$movieSubmission = new MovieSubmission();
		$movieSubmission->setTitle(self::getRandomMovieTitle())
			->setYear(
				self::shouldBeNull() ? null : $movie->getYear())
			->setPlot(
				self::shouldBeNull() ? null : $movie->getPlot())
			->addGenres(
				self::shouldBeNull() ? [] : $movie->getGenres())
			->addCountries(
				self::shouldBeNull() ? [] : $movie->getCountries())
			->addLangs(
				self::shouldBeNull() ? [] : $movie->getLangs())
			->addCastBulk(
				self::shouldBeNull() ? [] : $movie->getCast());
		return $movieSubmission;
	}


	public function load(ObjectManager $manager) {
		// add roles
		$roles = [
			Role::ROLE_USER				=>	'User',
			Role::ROLE_MODERATOR		=>	'Moderator',
			Role::ROLE_ADMINISTRATOR	=>	'Administrator'
		];

		foreach ($roles as $role => $roleName) {
			$roleObject = new Role();
			$roleObject->setRole($role)
				->setName($roleName);
			$manager->persist($roleObject);
		}
		$manager->flush();

		// load genres
		$loadedGenres = [];
		foreach (self::MOVIE_GENRES as $genreName) {
			$genre = new Genre();
			$genre->setName($genreName);

			$manager->persist($genre);
			$loadedGenres[] = $genre;
		}
		$this->genres = $loadedGenres;

		// load countries
		$loadedCountries = [];
		foreach (self::COUNTRIES as $countryName) {
			$country = new Country();
			$country->setName($countryName);

			$manager->persist($country);
			$loadedCountries[] = $country;
		}
		$this->countries = $loadedCountries;

		// load cast
		$loadedCast = [];
		foreach (self::NAMES as $name) {
			foreach (self::SURNAMES as $surname) {
				$person = new Person();
				$person->setName($name)
					->setSurname($surname);

				$manager->persist($person);
				$loadedCast[] = $person;
			}
		}
		$this->cast = $loadedCast;

		// load langs
		$loadedLangs = [];
		foreach (self::LANGUAGES as $langName) {
			$lang = new Lang();
			$lang->setName($langName);

			$manager->persist($lang);
			$loadedLangs[] = $lang;
		}
		$this->langs = $loadedLangs;

		$manager->flush();

		// load movies
		$loadedMovies = [];
		for ($i = 0; $i < self::AMOUNT_MOVIES; $i++) {
			$movie = new Movie();
			$movie->setTitle(self::getRandomMovieTitle() . $i)
				->setYear(mt_rand(2000, 2020))
				->setPlot(self::MOVIE_PLOT)
				->addGenres(self::getRandomGenres(1, 3)->toArray())
				->addCountries(self::getRandomCountries(1, 2)->toArray())
				->addLangs(self::getRandomLangs(1, 2)->toArray())
				->addCastBulk(self::getRandomCast(3, 20)->toArray());

			$manager->persist($movie);
			$loadedMovies[] = $movie;
		}

		// load defined users
		foreach (self::USERS as $userName => $userData) {
			$role = $manager->getRepository(Role::class)
				->findOneBy(['role' => $userData['role']]);
			$user = new User();
			$user->setName($userName)
				->setPassword($userData['password'])
				->setRole($role)
				->setEmail($userData['email']);

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

			$role = $manager->getRepository(Role::class)
				->findOneBy(['role' => ROLE::ROLE_USER]);
			$user = new User();
			$user->setName($name)
				->setPassword($password)
				->setRole($role)
				->setEmail($email);

			$manager->persist($user);
			$loadedUsers[] = $user;
		}

		$manager->flush();

		// load requests
		for ($i = 0; $i < self::AMOUNT_REQUESTS; $i++) {
			$action = mt_rand(0, 1000) < 5000 ?
				EnumMovieRequestAction::ACTION_ADD : EnumMovieRequestAction::ACTION_EDIT;
			$loadedUser = $loadedUsers[array_rand($loadedUsers)];
			$movieSubmission = self::getRandomMovieSubmission();

			$movieRequest = new MovieRequest();
			$movieRequest->setAction($action)
				->setMovieSubmission($movieSubmission)
				->setUser($loadedUser);

			if ($action == EnumMovieRequestAction::ACTION_EDIT) {
				$loadedMovie = $loadedMovies[array_rand($loadedMovies)];
				$movieRequest->setCurrentMovie($loadedMovie);
			}

			$manager->persist($movieSubmission);
			$manager->persist($movieRequest);
		}

		// load reviews
		for ($i = 0; $i < self::AMOUNT_REVIEWS; $i++) {
			$loadedUser = $loadedUsers[array_rand($loadedUsers)];
			$loadedMovie = $loadedMovies[array_rand($loadedMovies)];
			$shallAddComment = mt_rand(0, 10000) > 8000;

			$review = new Review();
			$review->setUser($loadedUser)
				->setMovie($loadedMovie)
				->setRating(mt_rand(1, 10))
				->setIsAccepted($shallAddComment ? mt_rand(0, 10000) > 5000 : true)
				->setContent($shallAddComment ? 'What a great movie' : null);
			if ($review->getContent()) {
				$review->setTitle('Wonderful experience');
			}

			$manager->persist($review);
		}

		$manager->flush();
	}
}
