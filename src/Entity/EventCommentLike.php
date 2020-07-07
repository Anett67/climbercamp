<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventCommentLikeRepository")
 * @UniqueEntity(
 * fields={"eventComment" = "postedBy"},
 * ) 
 */
class EventCommentLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventComment", inversedBy="eventCommentLikes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $eventComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventCommentLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventComment(): ?EventComment
    {
        return $this->eventComment;
    }

    public function setEventComment(?EventComment $eventComment): self
    {
        $this->eventComment = $eventComment;

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
}
