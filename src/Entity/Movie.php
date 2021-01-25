<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $plot;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $year;

	/**
	 * @ORM\Column(type="float", nullable=true)
	 */
	private $rating;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @ORM\ManyToMany(targetEntity=Person::class, inversedBy="participatedInMovies")
	 * @ORM\JoinTable(name="movie_cast")
	 */
	private $cast;

	/**
	 * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
	 */
	private $genres;

	/**
	 * @ORM\ManyToMany(targetEntity=Country::class)
	 */
	private $countries;

	/**
	 * @ORM\ManyToMany(targetEntity=Lang::class, inversedBy="movies")
	 */
	private $langs;

	/**
	 * @ORM\ManyToMany(targetEntity=User::class, mappedBy="watchlist")
	 */
	private $fanciedByUsers;

	/**
	 * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie", orphanRemoval=true)
	 */
	private $reviews;

	/**
	 * @ORM\OneToMany(targetEntity=MovieRequest::class, mappedBy="currentMovie")
	 */
	private $movieRequests;

	public function __construct() {
		$this->cast = new ArrayCollection();
		$this->genres = new ArrayCollection();
		$this->countries = new ArrayCollection();
		$this->langs = new ArrayCollection();
		$this->fanciedByUsers = new ArrayCollection();
		$this->reviews = new ArrayCollection();
		$this->movieRequests = new ArrayCollection();
		$this->created = new DateTime('now');
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function setTitle(string $title): self {
		$this->title = $title;

		return $this;
	}

	public function getPlot(): ?string {
		return $this->plot;
	}

	public function setPlot(?string $plot): self {
		$this->plot = $plot;

		return $this;
	}

	public function getYear(): ?int {
		return $this->year;
	}

	public function setYear(?int $year): self {
		$this->year = $year;

		return $this;
	}

	public function getRating(): ?float {
		return $this->rating;
	}

	public function setRating(?float $rating): self {
		$this->rating = $rating;

		return $this;
	}

	public function getCreated(): ?\DateTimeInterface {
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self {
		$this->created = $created;

		return $this;
	}

	/**
	 * @return Collection|Person[]
	 */
	public function getCast(): Collection {
		return $this->cast;
	}

	public function addCast(Person $person): self {
		if (!$this->cast->contains($person)) {
			$this->cast[] = $person;
		}

		return $this;
	}

	public function addCastBulk($people): self {
		foreach ($people as $person) {
			$this->addCast($person);
		}

		return $this;
	}

	public function removeCast(Person $cast): self {
		$this->cast->removeElement($cast);

		return $this;
	}

	/**
	 * @return Collection|Genre[]
	 */
	public function getGenres(): Collection {
		return $this->genres;
	}

	public function addGenre(Genre $genre): self {
		if (!$this->genres->contains($genre)) {
			$this->genres[] = $genre;
		}

		return $this;
	}

	public function addGenres($genres): self {
		foreach ($genres as $genre) {
			$this->addGenre($genre);
		}

		return $this;
	}

	public function removeGenre(Genre $genre): self {
		$this->genres->removeElement($genre);

		return $this;
	}

	/**
	 * @return Collection|Country[]
	 */
	public function getCountries(): Collection {
		return $this->countries;
	}

	public function addCountry(Country $country): self {
		if (!$this->countries->contains($country)) {
			$this->countries[] = $country;
		}

		return $this;
	}

	public function addCountries($countries): self {
		foreach ($countries as $country) {
			$this->addCountry($country);
		}

		return $this;
	}

	public function removeCountry(Country $country): self {
		$this->countries->removeElement($country);

		return $this;
	}

	/**
	 * @return Collection|Lang[]
	 */
	public function getLangs(): Collection {
		return $this->langs;
	}

	public function addLang(Lang $lang): self {
		if (!$this->langs->contains($lang)) {
			$this->langs[] = $lang;
		}

		return $this;
	}

	public function addLangs($langs): self {
		foreach ($langs as $lang) {
			$this->addLang($lang);
		}

		return $this;
	}

	public function removeLang(Lang $lang): self {
		$this->langs->removeElement($lang);

		return $this;
	}

	/**
	 * @return Collection|User[]
	 */
	public function getFanciedByUsers(): Collection {
		return $this->fanciedByUsers;
	}

	public function addFanciedByUser(User $fanciedByUser): self {
		if (!$this->fanciedByUsers->contains($fanciedByUser)) {
			$this->fanciedByUsers[] = $fanciedByUser;
			$fanciedByUser->addWatchlist($this);
		}

		return $this;
	}

	public function removeFanciedByUser(User $fanciedByUser): self {
		if ($this->fanciedByUsers->removeElement($fanciedByUser)) {
			$fanciedByUser->removeWatchlist($this);
		}

		return $this;
	}

	/**
	 * @return Collection|Review[]
	 */
	public function getReviews(): Collection {
		return $this->reviews;
	}

	public function addReview(Review $review): self {
		if (!$this->reviews->contains($review)) {
			$this->reviews[] = $review;
			$review->setMovie($this);
		}

		return $this;
	}

	public function removeReview(Review $review): self {
		if ($this->reviews->removeElement($review)) {
			// set the owning side to null (unless already changed)
			if ($review->getMovie() === $this) {
				$review->setMovie(NULL);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|MovieRequest[]
	 */
	public function getMovieRequests(): Collection {
		return $this->movieRequests;
	}

	public function addMovieRequest(MovieRequest $movieRequest): self {
		if (!$this->movieRequests->contains($movieRequest)) {
			$this->movieRequests[] = $movieRequest;
			$movieRequest->setCurrentMovie($this);
		}

		return $this;
	}

	public function removeMovieRequest(MovieRequest $movieRequest): self {
		if ($this->movieRequests->removeElement($movieRequest)) {
			// set the owning side to null (unless already changed)
			if ($movieRequest->getCurrentMovie() === $this) {
				$movieRequest->setCurrentMovie(NULL);
			}
		}

		return $this;
	}
}
