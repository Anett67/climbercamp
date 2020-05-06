<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventCommentRepository")
 */
class EventComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="eventComments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventCommentLike", mappedBy="eventComment")
     */
    private $eventCommentLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventCommentReply", mappedBy="eventComment")
     */
    private $eventCommentReplies;

    public function __construct()
    {
        $this->eventCommentLikes = new ArrayCollection();
        $this->eventCommentReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Collection|EventCommentLike[]
     */
    public function getEventCommentLikes(): Collection
    {
        return $this->eventCommentLikes;
    }

    public function addEventCommentLike(EventCommentLike $eventCommentLike): self
    {
        if (!$this->eventCommentLikes->contains($eventCommentLike)) {
            $this->eventCommentLikes[] = $eventCommentLike;
            $eventCommentLike->setEventComment($this);
        }

        return $this;
    }

    public function removeEventCommentLike(EventCommentLike $eventCommentLike): self
    {
        if ($this->eventCommentLikes->contains($eventCommentLike)) {
            $this->eventCommentLikes->removeElement($eventCommentLike);
            // set the owning side to null (unless already changed)
            if ($eventCommentLike->getEventComment() === $this) {
                $eventCommentLike->setEventComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventCommentReply[]
     */
    public function getEventCommentReplies(): Collection
    {
        return $this->eventCommentReplies;
    }

    public function addEventCommentReply(EventCommentReply $eventCommentReply): self
    {
        if (!$this->eventCommentReplies->contains($eventCommentReply)) {
            $this->eventCommentReplies[] = $eventCommentReply;
            $eventCommentReply->setEventComment($this);
        }

        return $this;
    }

    public function removeEventCommentReply(EventCommentReply $eventCommentReply): self
    {
        if ($this->eventCommentReplies->contains($eventCommentReply)) {
            $this->eventCommentReplies->removeElement($eventCommentReply);
            // set the owning side to null (unless already changed)
            if ($eventCommentReply->getEventComment() === $this) {
                $eventCommentReply->setEventComment(null);
            }
        }

        return $this;
    }

    public function isLikedByUser(User $user) : bool
    {
        foreach($this->eventCommentLikes as $like){
            if($like->getPostedBy() === $user ){
                return true;
            } 
        }

        return false;

    }
}
