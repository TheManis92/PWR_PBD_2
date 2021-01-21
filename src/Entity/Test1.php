<?php

namespace App\Entity;

use App\Repository\Test1Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Test1Repository::class)
 */
class Test1
{

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private $shortname;


    public function __construct()
    {

    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

}
