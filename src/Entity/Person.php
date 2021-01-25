<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $surname;

	/**
	 * @ORM\ManyToMany(targetEntity=Movie::class, mappedBy="cast")
	 */
	private $participatedInMovies;

	public function __construct() {
		$this->participatedInMovies = new ArrayCollection();
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

	public function getSurname(): ?string {
		return $this->surname;
	}

	public function setSurname(string $surname): self {
		$this->surname = $surname;

		return $this;
	}

	/**
	 * @return Collection|Movie[]
	 */
	public function getParticipatedInMovies(): Collection {
		return $this->participatedInMovies;
	}

	public function addParticipatedInMovie(Movie $participatedInMovie): self {
		if (!$this->participatedInMovies->contains($participatedInMovie)) {
			$this->participatedInMovies[] = $participatedInMovie;
			$participatedInMovie->addCast($this);
		}

		return $this;
	}

	public function removeParticipatedInMovie(Movie $participatedInMovie): self {
		if ($this->participatedInMovies->removeElement($participatedInMovie)) {
			$participatedInMovie->removeCast($this);
		}

		return $this;
	}
}
