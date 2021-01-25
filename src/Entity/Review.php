<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="smallint")
	 */
	private $rating;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $title;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $content;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isAccepted;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="reviews")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $movie;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	public function __construct() {
		$this->created = new DateTime('now');
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getRating(): ?int {
		return $this->rating;
	}

	public function setRating(int $rating): self {
		$this->rating = $rating;

		return $this;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function setTitle(?string $title): self {
		$this->title = $title;

		return $this;
	}

	public function getContent(): ?string {
		return $this->content;
	}

	public function setContent(?string $content): self {
		$this->content = $content;

		return $this;
	}

	public function getIsAccepted(): ?bool {
		return $this->isAccepted;
	}

	public function setIsAccepted(bool $isAccepted): self {
		$this->isAccepted = $isAccepted;

		return $this;
	}

	public function getCreated(): ?\DateTimeInterface {
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self {
		$this->created = $created;

		return $this;
	}

	public function getMovie(): ?Movie {
		return $this->movie;
	}

	public function setMovie(?Movie $movie): self {
		$this->movie = $movie;

		return $this;
	}

	public function getUser(): ?User {
		return $this->user;
	}

	public function setUser(?User $user): self {
		$this->user = $user;

		return $this;
	}
}
