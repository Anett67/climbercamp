<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 * @UniqueEntity(
 * fields={"nom" = "country"},
 * )
 */
class Ville
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="ville")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="villes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClimbingClub", mappedBy="ville")
     */
    private $climbingClubs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="ville")
     */
    private $events;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->climbingClubs = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $user->setVille($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getVille() === $this) {
                $user->setVille(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

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
            $climbingClub->setVille($this);
        }

        return $this;
    }

    public function removeClimbingClub(ClimbingClub $climbingClub): self
    {
        if ($this->climbingClubs->contains($climbingClub)) {
            $this->climbingClubs->removeElement($climbingClub);
            // set the owning side to null (unless already changed)
            if ($climbingClub->getVille() === $this) {
                $climbingClub->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setVille($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getVille() === $this) {
                $event->setVille(null);
            }
        }

        return $this;
    }
}
