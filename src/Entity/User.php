<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=64, unique=true)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=150, unique=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $joined;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $lastVisit;

	/**
	 * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $role;

	/**
	 * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="fanciedByUsers")
	 */
	private $watchlist;

	/**
	 * @ORM\OneToMany(targetEntity=Review::class, mappedBy="user", orphanRemoval=true)
	 */
	private $reviews;

	/**
	 * @ORM\OneToMany(targetEntity=MovieRequest::class, mappedBy="user", orphanRemoval=true)
	 */
	private $movieRequests;

	public function __construct() {
		$this->watchlist = new ArrayCollection();
		$this->reviews = new ArrayCollection();
		$this->movieRequests = new ArrayCollection();

		$this->joined = new DateTime('now');
		$this->lastVisit = new DateTime('now');
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function setName(string $name): self {
		$this->name = $name;

		return $this;
	}

	public function getPassword(): ?string {
		return $this->password;
	}

	public function setPassword(string $password): self {
		$password = password_hash($password, PASSWORD_BCRYPT);
		if ($password) {
			$this->password = $password;
		}
		return $this;
	}

	public function getEmail(): ?string {
		return $this->email;
	}

	public function setEmail(string $email): self {
		$this->email = $email;

		return $this;
	}

	public function getJoined(): ?\DateTimeInterface {
		return $this->joined;
	}

	public function setJoined(\DateTimeInterface $joined): self {
		$this->joined = $joined;

		return $this;
	}

	public function getLastVisit(): ?\DateTimeInterface {
		return $this->lastVisit;
	}

	public function setLastVisit(\DateTimeInterface $lastVisit): self {
		$this->lastVisit = $lastVisit;

		return $this;
	}

	public function getRole(): ?Role {
		return $this->role;
	}

	public function setRole(?Role $role): self {
		$this->role = $role;

		return $this;
	}

	/**
	 * @return Collection|Movie[]
	 */
	public function getWatchlist(): Collection {
		return $this->watchlist;
	}

	public function addWatchlist(Movie $watchlist): self {
		if (!$this->watchlist->contains($watchlist)) {
			$this->watchlist[] = $watchlist;
		}

		return $this;
	}

	public function removeWatchlist(Movie $watchlist): self {
		$this->watchlist->removeElement($watchlist);

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
			$review->setUser($this);
		}

		return $this;
	}

	public function removeReview(Review $review): self {
		if ($this->reviews->removeElement($review)) {
			// set the owning side to null (unless already changed)
			if ($review->getUser() === $this) {
				$review->setUser(NULL);
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
			$movieRequest->setUser($this);
		}

		return $this;
	}

	public function removeMovieRequest(MovieRequest $movieRequest): self {
		if ($this->movieRequests->removeElement($movieRequest)) {
			// set the owning side to null (unless already changed)
			if ($movieRequest->getUser() === $this) {
				$movieRequest->setUser(NULL);
			}
		}

		return $this;
	}

	public function getRoles() {
		$roles[] = ROLE::ROLE_USER;
		if ($this->role !== NULL) {
			$roles[] = $this->role->getRole();
		}

		return array_unique($roles);
	}

	public function getSalt() {
		return NULL;
	}

	public function getUsername() {
		return $this->email;
	}

	public function eraseCredentials() {
		// empty
	}
}
