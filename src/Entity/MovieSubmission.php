<?php

namespace App\Entity;

use App\Repository\MovieSubmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieSubmissionRepository::class)
 */
class MovieSubmission {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
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
	 * @ORM\ManyToMany(targetEntity=Person::class)
	 */
	private $directors;

	/**
	 * @ORM\ManyToMany(targetEntity=Person::class)
     * @ORM\JoinTable(name="movie_submissions_cast")
	 */
	private $cast;

	/**
	 * @ORM\ManyToMany(targetEntity=Genre::class)
	 */
	private $genres;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class)
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="name", referencedColumnName="name"),
     *      @ORM\JoinColumn(name="short_name", referencedColumnName="short_name")
     * )
     */
	private $countries;

	/**
	 * @ORM\ManyToMany(targetEntity=Lang::class)
	 */
	private $langs;

	/**
	 * @ORM\OneToOne(targetEntity=MovieRequest::class, mappedBy="movieSubmission", cascade={"persist", "remove"})
	 */
	private $movieRequest;

	public function __construct() {
		$this->directors = new ArrayCollection();
		$this->cast = new ArrayCollection();
		$this->genres = new ArrayCollection();
		$this->countries = new ArrayCollection();
		$this->langs = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function setTitle(?string $title): self {
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

	/**
	 * @return Collection|Person[]
	 */
	public function getDirectors(): Collection {
		return $this->directors;
	}

	public function addDirector(Person $director): self {
		if (!$this->directors->contains($director)) {
			$this->directors[] = $director;
		}

		return $this;
	}

	public function removeDirector(Person $director): self {
		$this->directors->removeElement($director);

		return $this;
	}

	/**
	 * @return Collection|Person[]
	 */
	public function getCast(): Collection {
		return $this->cast;
	}

	public function addCast(Person $cast): self {
		if (!$this->cast->contains($cast)) {
			$this->cast[] = $cast;
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

	public function removeLang(Lang $lang): self {
		$this->langs->removeElement($lang);

		return $this;
	}

	public function getMovieRequest(): ?MovieRequest {
		return $this->movieRequest;
	}

	public function setMovieRequest(?MovieRequest $movieRequest): self {
		// unset the owning side of the relation if necessary
		if ($movieRequest === NULL && $this->movieRequest !== NULL) {
			$this->movieRequest->setMovieSubmission(NULL);
		}

		// set the owning side of the relation if necessary
		if ($movieRequest !== NULL && $movieRequest->getMovieSubmission() !== $this) {
			$movieRequest->setMovieSubmission($this);
		}

		$this->movieRequest = $movieRequest;

		return $this;
	}
}
