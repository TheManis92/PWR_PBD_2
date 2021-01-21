<?php

namespace App\Entity;

use App\Repository\Test2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Test2Repository::class)
 */
class Test2
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Test1::class)
     * @ORM\JoinColumns(
     *      @ORM\JoinColumn(name="name", referencedColumnName="name"),
     *      @ORM\JoinColumn(name="shortname", referencedColumnName="shortname")
     * )
     */
    private $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Test1[]
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Test1 $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries[] = $country;
        }

        return $this;
    }

    public function removeCountry(Test1 $country): self
    {
        $this->countries->removeElement($country);

        return $this;
    }
}
