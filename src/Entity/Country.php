<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country {
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private $shortName;


    public function __construct() {
        $this->movies = new ArrayCollection();
    }


    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addCountry($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self {
        if ($this->movies->removeElement($movie)) {
            $movie->removeCountry($this);
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }
}
