<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClimbingCategorieRepository")
 * @UniqueEntity(
 * fields={"label"},
 * message="La catégorie existe déjà"
 * )
 */
class ClimbingCategorie
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue()
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="climbingCategorie")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClimbingClub", mappedBy="climbingCategories")
     */
    private $climbingClubs;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->climbingClubs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addClimbingCategorie($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeClimbingCategorie($this);
        }

        return $this;
    }

    /**
     * @return Collection|ClimbingClub[]
     */
    public function getClimbingClubs(): Collection
    {
        return $this->climbingClubs;
    }

    public function addClimbingClub(ClimbingClub $climbingClub): self
    {
        if (!$this->climbingClubs->contains($climbingClub)) {
            $this->climbingClubs[] = $climbingClub;
            $climbingClub->addClimbingCategory($this);
        }

        return $this;
    }

    public function removeClimbingClub(ClimbingClub $climbingClub): self
    {
        if ($this->climbingClubs->contains($climbingClub)) {
            $this->climbingClubs->removeElement($climbingClub);
            $climbingClub->removeClimbingCategory($this);
        }

        return $this;
    }
}
