<?php

namespace App\Entity;

use App\Repository\MovieRequestRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRequestRepository::class)
 */
class MovieRequest {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="enum_movie_request_action")
	 */
	private $action;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @ORM\OneToOne(targetEntity=MovieSubmission::class, inversedBy="movieRequest", cascade={"persist", "remove"})
	 */
	private $movieSubmission;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movieRequests")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="movieRequests")
	 */
	private $currentMovie;

	public function __construct() {
		$this->created = new DateTime('now');
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getAction() {
		return $this->action;
	}

	public function setAction($action): self {
		$this->action = $action;

		return $this;
	}

	public function getCreated(): ?\DateTimeInterface {
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self {
		$this->created = $created;

		return $this;
	}

	public function getMovieSubmission(): ?MovieSubmission {
		return $this->movieSubmission;
	}

	public function setMovieSubmission(?MovieSubmission $movieSubmission): self {
		$this->movieSubmission = $movieSubmission;

		return $this;
	}

	public function getUser(): ?User {
		return $this->user;
	}

	public function setUser(?User $user): self {
		$this->user = $user;

		return $this;
	}

	public function getCurrentMovie(): ?Movie {
		return $this->currentMovie;
	}

	public function setCurrentMovie(?Movie $currentMovie): self {
		$this->currentMovie = $currentMovie;

		return $this;
	}
}
