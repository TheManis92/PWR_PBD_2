<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned":true})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=32, unique=true)
	 */
	private $role;

	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity=User::class, mappedBy="role", orphanRemoval=true)
	 */
	private $users;

	public function __construct() {
		$this->users = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getRole(): ?string {
		return $this->role;
	}

	public function setRole(string $role): self {
		$this->role = $role;

		return $this;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function setName(string $name): self {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection|User[]
	 */
	public function getUsers(): Collection {
		return $this->users;
	}

	public function addUser(User $user): self {
		if (!$this->users->contains($user)) {
			$this->users[] = $user;
			$user->setRole($this);
		}

		return $this;
	}

	public function removeUser(User $user): self {
		if ($this->users->removeElement($user)) {
			// set the owning side to null (unless already changed)
			if ($user->getRole() === $this) {
				$user->setRole(NULL);
			}
		}

		return $this;
	}
}
