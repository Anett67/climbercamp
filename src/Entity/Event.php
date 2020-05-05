<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @UniqueEntity(
 * fields={"interestedUsers"},
 * )
 */
class Event
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $eventDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     */
    private $postedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="savedEvents")
     */

    private $interestedUsers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventLike", mappedBy="event")
     */
    private $eventLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventComment", mappedBy="event")
     */
    private $eventComments;

    public function __construct()
    {
        $this->interestedUsers = new ArrayCollection();
        $this->eventLikes = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(?\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInterestedUsers(): Collection
    {
        return $this->interestedUsers;
    }

    public function addInterestedUser(User $interestedUser): self
    {
        if (!$this->interestedUsers->contains($interestedUser)) {
            $this->interestedUsers[] = $interestedUser;
        }

        return $this;
    }

    public function removeInterestedUser(User $interestedUser): self
    {
        if ($this->interestedUsers->contains($interestedUser)) {
            $this->interestedUsers->removeElement($interestedUser);
        }

        return $this;
    }

    /**
     * @return Collection|EventLike[]
     */
    public function getEventLikes(): Collection
    {
        return $this->eventLikes;
    }

    public function addEventLike(EventLike $eventLike): self
    {
        if (!$this->eventLikes->contains($eventLike)) {
            $this->eventLikes[] = $eventLike;
            $eventLike->setEvent($this);
        }

        return $this;
    }

    public function removeEventLike(EventLike $eventLike): self
    {
        if ($this->eventLikes->contains($eventLike)) {
            $this->eventLikes->removeElement($eventLike);
            // set the owning side to null (unless already changed)
            if ($eventLike->getEvent() === $this) {
                $eventLike->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventComment[]
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        if (!$this->eventComments->contains($eventComment)) {
            $this->eventComments[] = $eventComment;
            $eventComment->setEvent($this);
        }

        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        if ($this->eventComments->contains($eventComment)) {
            $this->eventComments->removeElement($eventComment);
            // set the owning side to null (unless already changed)
            if ($eventComment->getEvent() === $this) {
                $eventComment->setEvent(null);
            }
        }

        return $this;
    }
}
